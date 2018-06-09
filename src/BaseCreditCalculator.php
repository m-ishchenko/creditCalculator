<?php
namespace maximishchenko\credit_calculator;

use maximishchenko\credit_calculator\Base;

/**
 * 
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 */
abstract class BaseCreditCalculator
{
	/**
	 * Процент от цены для расчета суммы КАСКО
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м
	 */
	protected $cascoPercentages;

	/**
	 * Учитывать КАСКО
	 * 
	 * @access protected
	 * @var integer необходимость учитывать КАСКО
	 */
	protected $needCasco;

	/**
	 * Стоимость КАСКО
	 * 
	 * @access protected
	 * @var float стоимость КАСКО, руб.
	 */
	protected $cascoPrice;

	/**
	 * Учитывать страхование жизни
	 * 
	 * @access  protected
	 * @var boolean
	 */
	protected $needInsurance;

	/**
	 * Процент от стоимости а/м для расчета суммы страхования жизни
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м, %
	 */
	protected $insurancePercentages;

	/**
	 * Сумма страхования жизни, руб
	 * 
	 * @access  protected
	 * @var float страхование жизни, руб
	 */
	protected $insurancePrice;

	/**
	 * Учитывать отложенный платеж
	 * 
	 * @access  protected
	 * @var boolean необходимость учитывать отложенный платеж
	 */
	protected $needDeferred;

	/**
	 * Процент от первоначальной стоимости а/м для расчета суммы отложенного платежа, %
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м, %
	 */
	protected $deferredPercentages;

	/**
	 * Сумма отложенного платежа, руб
	 * 
	 * @access protected
	 * @var float сумма отложенного платежа, руб
	 */
	protected $deferredPrice;

	/**
	 * Сумма процентов отложенного платежа
	 * 
	 * @access protected
	 * @var float сумма, подлежащая уплате по процентам отложенного платежа, руб
	 */
	protected $deferredPercentagesPrice;

	/**
	 * Итоговая стоимость а/м
	 * 
	 * @access protected
	 * @var float стоимость а/м, руб
	 */
	protected $carPrice;

	/**
	 * Размер первоначального взноса, %
	 * 
	 * @access protected
	 * @var integer первоначальный взнос, %
	 */
	protected $firstPaymentPercentage;

	/**
	 * Срок кредита, мес
	 * 
	 * @access protected
	 * @var integer срок кредита, мес
	 */
	protected $creditTime;

	/**
	 * Размер процентной ставки по кредиту
	 * 
	 * @access protected
	 * @var integer процентная ставка, %
	 */
	protected $interestRate;

	/**
	 * Стоимость а/м, руб
	 * 
	 * @access  public
	 * @return float стоимость а/м, руб
	 */
	abstract public function getCarPrice();

	/**
	 * Расчет первоначального взноса, руб
	 * 
	 * @access  public
	 * @return float первоначальный взнос, руб
	 */
	abstract public function getInitialPayment();

	/**
	 * Расчет суммы кредита, руб
	 * 
	 * @access  public
	 * @return float сумма кредита, руб
	 */
	abstract public function getAmountOfCredit();

	/**
	 * Расчет первоначального взноса, %
	 * 
	 * @access  public
	 * @return integer первоначальный взнос, %
	 */
	abstract public function getInitialPaymentPercentages();

	/**
	 * Срок кредита, мес
	 * 
	 * @access  public
	 * @return integer срок кредита, мес
	 */
	abstract public function getCreditTime();

	/**
	 * Процентная ставка, %
	 * 
	 * @access  public
	 * @return integer процентная ставка, %
	 */
	abstract public function getInterestRate();

	/**
	 * Расчет ежемесячного платежа, руб.
	 * 
	 * @access  public
	 * @return float ежемесячный платеж, руб.
	 */
	abstract public function getMonthlyPayment();

	/**
	 * Расчет стоимости каско, руб
	 * 
	 * @access  public
	 * @return float расчет стоимости каско, руб
	 */
	abstract public function getCascoPrice();

	/**
	 * Расчет стоимости страхования жизни, руб
	 * 
	 * @access  public
	 * @return float расчет стоимости страхования жизни, руб
	 */
	abstract public function getInsurancePrice();

	/**
	 * Возвращает процентную ставку расчета КАСКО, %
	 * 
	 * @access  public
	 * @return integer процентная ставка расчета КАСКО, %
	 */
	abstract public function getCascoPercentages();

	/**
	 * Возвращает процентную ставку расчета страхования жизни, %
	 * 
	 * @access  public
	 * @return integer процентная ставка страхования жизни, %
	 */
	abstract public function getInsurancePercentages();

	/**
	 * Расчет стоимости остаточного платежа, руб.
	 * 
	 * @access  public
	 * @return decimal(65.2) стоимость остаточного платежа, руб.
	 */
	abstract public function getDeferredPaymentPrice();

	/**
	 * Возвращает процентную ставку отложенного платежа, %
	 * 
	 * @access  public
	 * @return integer процентная ставка отложенного платежа, %
	 */
	abstract public function getDeferredPercentages();


	/**
	 * Расчет числового коэффициента значения процентной ставки
	 * 
	 * @access protected
	 * @return float числовое значение процентной ставки
	 */
	protected function getInterestRateNumeric() {
		return $this->getInterestRate() / Base::PERCENTAGES_100;
	}

	/**
	 * Расчет месячной процентной ставки по кредиту
	 * 
	 * @access protected
	 * @return float месячная процентная ставка по кредиту
	 */
	protected function getMonthlyPercentages() {
		return $this->getInterestRateNumeric() / Base::MONTHS_IN_YEAR;
	}

	/**
	 * Расчет коэффициента аннуитета
	 * 
	 * @access protected
	 * @return float коэффициент аннуитета
	 */
	protected function getAnnuityCoefficient() {
		$i = pow(1 + $this->getMonthlyPercentages(), $this->creditTime);
		return ($this->getMonthlyPercentages() * $i) / ($i - 1);
	}
}