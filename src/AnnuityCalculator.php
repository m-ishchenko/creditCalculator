<?php

namespace img\credit_calculator;

use img\credit_calculator\interfaces\CreditCalculatorInterface;
use img\credit_calculator\base\Base;
use img\credit_calculator\services\Casco;
use img\credit_calculator\services\Insurance;
use img\credit_calculator\services\Deferred;
use img\credit_calculator\services\CreditData;

/**
 * Расчет аннуитета автокредита
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 * @final
 */
final class AnnuityCalculator extends BaseCreditCalculator implements CreditCalculatorInterface
{
	
	/**
	 * Установка входных параметров при инициализаци
	 * 
	 * @param CreditData $credit    данные кредита
	 * @param Casco      $casco     расчеты КАСКО
	 * @param Insurance  $insurance расчеты страхования жизни
	 * @param Deferred   $deferred  расчеты отложенного платежа
	 */
	function __construct(CreditData $credit, Casco $casco, Insurance $insurance, Deferred $deferred) {
		$this->constructCreditData($credit);
        $this->setServicePreference($casco);
        $this->setServicePreference($insurance);
        $this->setServicePreference($deferred);
	}

	/**
	 * Возвращает стоимость а/м, руб.
	 * 
	 * @access public
	 * @return float заявленная стоимость а/м, руб
	 */
	public function getCarPrice() {
	    $carPrice = ($this->needCasco) ? $this->carPrice + $this->getCascoPrice() : $this->carPrice;
		return Base::setRoundedValue($carPrice);
	}

	/**
	 * Возвращает размер первоначального взноса, руб
	 * 
	 * ```
	 * ПВ = (СА + СК) * РПВ / 100
	 * где:
	 * ПВ - размер первоначального взноса
	 * СА - стоимость а/м
	 * РПВ - размер первоначального взноса
	 * СК - стоимость КАСКО
	 * ```
	 * 
	 * @access  public
	 * @return float сумма первоначального взноса
	 */
	public function getInitialPayment() {
	    $carPrice = $this->getCarPrice();
	    $firstPaymentPercentages = $this->firstPaymentPercentage;
        $initialPayment = ($carPrice * $firstPaymentPercentages) / Base::PERCENTAGES_100;
		return Base::setRoundedValue($initialPayment);
	}

	/**
	 * Возвращает размер первоначального взноса, %
	 * 
	 * @access public
	 * @return integer размер первоначального взноса, %
	 */
	public function getInitialPaymentPercentages() {
		return $this->firstPaymentPercentage;
	}

	/**
	 * Возвращает сумму кредита, за вычетом первоначального взноса, руб.
	 * 
	 * ```
	 * СКр = (СА + СК) - ПВ
	 * где:
	 * СКр - сумма кредита
	 * СА - стоимость а/м
	 * СК - стоимость КАСКО
	 * ```
	 * 
	 * @access  public
	 * @return float сумма кредита, руб
	 */
	public function getAmountOfCredit() {
	    $carPrice = $this->carPrice;
	    $cascoPrice = $this->cascoPrice;
	    $initialPayment = $this->getInitialPayment();
		$amount = $carPrice - $initialPayment;
		$amount = ($this->needCasco) ? $amount + $cascoPrice : $amount;
        $amount = ($this->needDeferred) ? $amount - $this->deferredPrice : $amount;
		return Base::setRoundedValue($amount);
	}

	/**
	 * Возвращает срок кредита, мес
	 * 
	 * @access public
	 * @return integer срок кредита, мес
	 */
	public function getCreditTime() {
		return $this->creditTime;
	}

	/**
	 * Возвращает размер процентной ставки, %
	 * 
	 * @access public
	 * @return float размер процентной ставки, %
	 */
	public function getInterestRate() {
		return $this->interestRate;
	}

	/**
	 * Расчет ежемесячного платежа, руб
	 * 
	 * @access public
	 * @return float ежемесячный платеж, руб
	 */
	public function getMonthlyPayment() {
	    $creditAmount = $this->getAmountOfCredit();
		$annuityCoefficient = $this->getAnnuityCoefficient();
        $basepayment = $annuityCoefficient * $creditAmount;
        $payment = $basepayment + $this->getInsuranceMonthlyPayment() + $this->deferredPercentagesPrice;
		return Base::setRoundedValue($payment);		
	}

	/**
	 * Расчет стоимости КАСКО, руб
	 * 
	 * @access  public
	 * @return float стоимость КАСКО, руб
	 */
	public function getCascoPrice() {
		return Base::setRoundedValue($this->cascoPrice);		
	}

	/**
	 * Расчет стоимости страхования жизни, руб
	 * 
	 * @access  public
	 * @return float стоимость страхования жизни, руб
	 */
	public function getInsurancePrice() {
		return Base::setRoundedValue($this->insurancePrice);
	}

	/**
	 * Возвращает размер процентной ставки для расчета стоимости КАСКО, %
	 * 
	 * @access  public
	 * @return integer размер процентной ставки для расчета стоимости КАСКО, %
	 */
	public function getCascoPercentages() {
		return $this->cascoPercentages;
	}

	/**
	 * Возвращает размер процентной ставки для расчета страхования жизни, %
	 * 
	 * @access  public
	 * @return integer процентная ставка для расчета страхования жизни, %
	 */
	public function getInsurancePercentages() {
		return $this->insurancePercentages;
	}

	/**
	 * Возвращает размер отложенного платежа, руб.
	 * 
	 * @access  public
	 * @return float размер отложенного платежа, руб
	 */
	public function getDeferredPaymentPrice() {
		return Base::setRoundedValue($this->deferredPrice);
	}

	/**
	 * Возвращает размер процентной ставки отложенного платежа, %
	 * 
	 * @access  public
	 * @return integer размер процентной ставки отложенного платежа, %
	 */
	public function getDeferredPercentages() {
		return $this->deferredPercentages;
	}

    /**
     * @param CreditData $credit
     */
    protected function constructCreditData(CreditData $credit) {
        $this->carPrice = $credit->getCarPrice();
        $this->firstPaymentPercentage = $credit->getFirstPaymentPercentages();
        $this->creditTime = $credit->getCreditTime();
        $this->interestRate = $credit->getInterestRate();
    }

    /**
     * @param $preferences
     * @throws \Exception
     */
    protected function setServicePreference($preferences) {
        if($preferences instanceof Casco) {
            $carPrice = $this->carPrice;
            $this->needCasco = $preferences->isNeedable();
            $this->cascoPercentages = $preferences->getPercentages();
            $this->cascoPrice = $preferences->setPrice($carPrice);
        } elseif($preferences instanceof Insurance) {
            $creditAmount = $this->getAmountOfCredit();
            $this->needInsurance = $preferences->isNeedable();
            $this->insurancePercentages = $preferences->getPercentages();
            $this->insurancePrice = $preferences->setPrice($creditAmount);
        } elseif($preferences instanceof Deferred) {
            $carPrice = $this->carPrice;
            $interestRate = $this->interestRate;
            $this->needDeferred = $preferences->isNeedable();
            $this->deferredPercentages = $preferences->getPercentages();
            $this->deferredPrice = $preferences->setPrice($this->carPrice);
            $this->deferredPercentagesPrice = $preferences->setDeferredPercentagesPrice($carPrice, $interestRate);
        } else {
            throw new \Exception('Некорректный аргумент');
        }
    }

    /**
     * @return float|int
     */
    protected function getInsuranceMonthlyPayment() {
        return $this->insurancePrice / $this->creditTime;
    }
}

