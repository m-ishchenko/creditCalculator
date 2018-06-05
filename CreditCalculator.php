<?php
namespace creditCalc;

require_once 'autoload.php';

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
final class CreditCalculator implements CreditCalculatorInterface
{
	/**
	 * Процент от цены для расчета суммы КАСКО
	 * 
	 * @access  private
	 * @var integer процент стоимости а/м
	 */
	private $cascoPercentages;

	/**
	 * Учитывать КАСКО
	 * 
	 * @access private
	 * @var integer необходимость учитывать КАСКО
	 */
	private $needCasco;

	/**
	 * Стоимость КАСКО
	 * 
	 * @access private
	 * @var float стоимость КАСКО, руб.
	 */
	private $cascoPrice;

	/**
	 * Учитывать страхование жизни
	 * 
	 * @access  private
	 * @var boolean
	 */
	private $needInsurance;

	/**
	 * Процент от стоимости а/м для расчета суммы страхования жизни
	 * 
	 * @access  private
	 * @var integer процент стоимости а/м, %
	 */
	private $insurancePercentages;

	/**
	 * Сумма страхования жизни, руб
	 * 
	 * @access  private
	 * @var float страхование жизни, руб
	 */
	private $insurancePrice;

	/**
	 * Учитывать отложенный платеж
	 * 
	 * @access  private
	 * @var boolean необходимость учитывать отложенный платеж
	 */
	private $needDeferred;

	/**
	 * Процент от первоначальной стоимости а/м для расчета суммы отложенного платежа, %
	 * 
	 * @access  private
	 * @var integer процент стоимости а/м, %
	 */
	private $deferredPercentages;

	/**
	 * Сумма отложенного платежа, руб
	 * 
	 * @access private
	 * @var float сумма отложенного платежа, руб
	 */
	private $deferredPrice;

	/**
	 * Сумма процентов отложенного платежа
	 * 
	 * @access private
	 * @var float сумма, подлежащая уплате по процентам отложенного платежа, руб
	 */
	private $deferredPercentagesPrice;

	/**
	 * Итоговая стоимость а/м
	 * 
	 * @access private
	 * @var float стоимость а/м, руб
	 */
	private $carPrice;

	/**
	 * Размер первоначального взноса, %
	 * 
	 * @access private
	 * @var integer первоначальный взнос, %
	 */
	private $firstPaymentPercentage;

	/**
	 * Срок кредита, мес
	 * 
	 * @access private
	 * @var integer срок кредита, мес
	 */
	private $creditTime;

	/**
	 * Размер процентной ставки по кредиту
	 * 
	 * @access private
	 * @var integer процентная ставка, %
	 */
	private $interestRate;
	
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
		return SharedValues::setRoundedValue($this->carPrice);
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
	 * @return decimal сумма первоначального взноса
	 */
	public function getInitialPayment() {
		if($this->needCasco) {
			$carPrice = $this->getCarPrice() + $this->getCascoPrice();
			$initialPayment = ($carPrice * $this->firstPaymentPercentage) / SharedValues::PERCENTAGES_100;
		} else {
			$initialPayment = ($this->getCarPrice() * $this->firstPaymentPercentage) / SharedValues::PERCENTAGES_100;
		}
		return SharedValues::setRoundedValue($initialPayment);
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
	 * @return decimal сумма кредита, руб
	 */
	public function getAmountOfCredit() {
		$amount = $this->carPrice - $this->getInitialPayment();
		$amount = ($this->needCasco) ? $amount + $this->cascoPrice : $amount;
		return SharedValues::setRoundedValue($amount);
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
	 * @return decimal размер процентной ставки, %
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
		return SharedValues::setRoundedValue($payment);		
	}

	/**
	 * Расчет стоимости КАСКО, руб
	 * 
	 * @access  public
	 * @return float стоимость КАСКО, руб
	 */
	public function getCascoPrice() {
		return SharedValues::setRoundedValue($this->cascoPrice);		
	}

	/**
	 * Расчет стоимости страхования жизни, руб
	 * 
	 * @access  public
	 * @return float стоимость страхования жизни, руб
	 */
	public function getInsurancePrice() {
		return SharedValues::setRoundedValue($this->insurancePrice);
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
		return SharedValues::setRoundedValue($this->deferredPrice);
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
	 * Расчет числового коэффициента значения процентной ставки
	 * 
	 * @access private
	 * @return float числовое значение процентной ставки
	 */
	private function getInterestRateNumeric() {
		return $this->getInterestRate() / SharedValues::PERCENTAGES_100;
	}

	/**
	 * Расчет месячной процентной ставки по кредиту
	 * 
	 * @access private
	 * @return float месячная процентная ставка по кредиту
	 */
	private function getMonthlyPercentages() {
		return $this->getInterestRateNumeric() / SharedValues::MONTHS_IN_YEAR;
	}

	/**
	 * Расчет коэффициента аннуитета
	 * 
	 * @access private
	 * @return float коэффициент аннуитета
	 */
	private function getAnnuityCoefficient() {
		$i = pow(1 + $this->getMonthlyPercentages(), $this->creditTime);
		return ($this->getMonthlyPercentages() * $i) / ($i - 1);
	}
}

