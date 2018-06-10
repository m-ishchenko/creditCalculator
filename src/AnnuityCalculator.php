<?php
namespace maximishchenko\credit_calculator;

/**
 * Расчет аннуитета автокредита
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html>
 * @version 1.0
 * @final
 */
final class AnnuityCalculator extends BaseCreditCalculator
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

		// Условия кредита
		$this->carPrice = $credit->getCarPrice();
		$this->firstPaymentPercentage = $credit->getFirstPaymentPercentages();
		$this->creditTime = $credit->getCreditTime();
		$this->interestRate = $credit->getInterestRate();

		// КАСКО
		$this->needCasco = $casco->isNeedCasco();
		$this->cascoPercentages = $casco->getCascoPercentages();
		$this->cascoPrice = ($this->needCasco) ? $casco->setCascoPrice($this->carPrice) : 0;

		// Страхование жизни
		$this->needInsurance = $insurance->isNeedInsurance();
		$this->insurancePercentages = $insurance->getInsurancePercentages();
		$this->insurancePrice = ($this->needInsurance) ? $insurance->setInsurancePrice($this->getAmountOfCredit()) : 0;

		// Отложенный платеж		
		$this->needDeferred = $deferred->isNeedDeferred();
		$this->deferredPercentages = $deferred->getDeferredPercentages();
		$this->deferredPrice = ($this->needDeferred) ? $deferred->setDeferredPrice($this->carPrice) : 0;
		$this->deferredPercentagesPrice = ($this->needDeferred) ? $deferred->setDeferredPercentagesPrice($this->carPrice, $this->interestRate) : 0;
	}

	/**
	 * Возвращает стоимость а/м, руб.
	 * 
	 * @access public
	 * @return float заявленная стоимость а/м, руб
	 */
	public function getCarPrice() {
		return Base::setRoundedValue($this->carPrice);
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
		if($this->needCasco) {
			$carPrice = $this->getCarPrice() + $this->getCascoPrice();
			$initialPayment = ($carPrice * $this->firstPaymentPercentage) / Base::PERCENTAGES_100;
		} else {
			$initialPayment = ($this->getCarPrice() * $this->firstPaymentPercentage) / Base::PERCENTAGES_100;
		}
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
		$amount = $this->carPrice - $this->getInitialPayment();
		$amount = ($this->needCasco) ? $amount + $this->cascoPrice : $amount;
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
		$creditAmount = ($this->needDeferred) ? $creditAmount - $this->deferredPrice : $creditAmount;
		$payment = $this->getAnnuityCoefficient() * $creditAmount;
		$payment = ($this->needInsurance) ? $payment + ($this->insurancePrice / $this->creditTime) : $payment;		
		$payment = ($this->needDeferred) ? $payment + $this->deferredPercentagesPrice : $payment;
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
}

