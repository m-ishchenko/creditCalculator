<?php
namespace creditCalc;

require_once 'autoload.php';

use creditCalc\CreditCalculatorInterface;
use creditCalc\CreditCalculatorValidatorsTrait;

/**
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @todo Добавить расчет КАСКО, остаточный платеж
 * 
 * Каско - ок. 6% от стоимости а/м (на текущий момент, в качестве примера - 0.71)
 * Каско прибавляется к сумме кредита, также начисляются %
 */
class CreditCalculator implements CreditCalculatorInterface
{
	use CreditCalculatorValidatorsTrait;

	/**
	 * Числовое значение 100%
	 */
	const PERCENTAGES_100 = 100;

	/**
	 * Числовое значение - количество месяцев в году
	 */
	const MONTHS_IN_YEAR = 12;

	/**
	 * Процент от цены для расчета суммы каско
	 * @var integer
	 */
	public $cascoPercentages;

	/**
	 * Учитывать каско
	 * @access public
	 * @var integer
	 */
	public $casco;

	/**
	 * Сумма каско
	 * @access private
	 * @var decimal(65.2)
	 */
	private $cascoPrice;

	/**
	 * Итоговая стоимость а/м
	 * @access private
	 * @var decimal(65.2)
	 */
	private $carPrice;

	/**
	 * Размер первоначального взноса, %
	 * @access private
	 * @var integer
	 */
	private $firstPaymentPercentage;

	/**
	 * Срок кредита, мес
	 * @access private
	 * @var integer
	 */
	private $creditTime;

	/**
	 * Размер процентной ставки по кредиту
	 * @access private
	 * @var integer
	 */
	private $interestRate;
	
	/**
	 * Установка входных параметров при инициализаци
	 * @param decimal(65.2) $carPrice               стоимость а/м, руб.
	 * @param integer $firstPaymentPercentage размер первоначального взноса, %
	 * @param integer $creditTime             срок кредита, мес
	 * @param decimal(65.2) $interestRate             процентная ставка, мес
	 */
	function __construct($carPrice, $firstPaymentPercentage, $creditTime, $interestRate, $casco = 0, $cascoPercentages)
	{

		/**
		 * Валидация переданных значений
		 */
		try {

			$this->validateNumber($carPrice);
			$this->validateInteger($firstPaymentPercentage);
			$this->validateInteger($creditTime);
			$this->validateNumber($interestRate);

		} catch (Exception $e) {
			echo 'Ошибка: ' .$e->getMessage();
		}

		$this->carPrice = $carPrice;
		$this->firstPaymentPercentage = $firstPaymentPercentage;
		$this->creditTime = $creditTime;
		$this->interestRate = $interestRate;
		$this->casco = $casco;

		$this->cascoPercentages = $cascoPercentages;
		$this->cascoPrice = ($this->casco == 1) ? $this->setCascoPrice() : 0;
	}

	/**
	 * возвращает стоимость а/м, руб.
	 * @access public
	 * @return decimal(65.2) заявленная стоимость а/м
	 */
	public function getCarPrice() {
		return $this->setRoundedValue($this->carPrice);
	}

	/**
	 * Использует цену а/м и процент первоначального взноса, возвращает сумму первоначального взноса, руб
	 * @return decimal(65.2) сумма первоначального взноса
	 */
	public function getInitialPayment() {

		if($this->casco == 1) {
			$carPrice = $this->carPrice + $this->getCascoPrice();
			$initialPayment = ($carPrice * $this->firstPaymentPercentage) / self::PERCENTAGES_100;
		} else {
			$initialPayment = ($this->carPrice * $this->firstPaymentPercentage) / self::PERCENTAGES_100;
		}

		return $this->setRoundedValue($initialPayment);
	}

	/**
	 * Возвращает первоначальный взнос, %
	 * @return integer размер первоначального взноса, %
	 */
	public function getInitialPaymentPercentages() {
		return $this->firstPaymentPercentage;
	}

	/**
	 * Возвращает сумму кредита, за вычетом первоначального взноса, руб.
	 * @return decimal(65.2) сумма кредита, руб
	 */
	public function getAmountOfCredit() {
		$amount = $this->carPrice - $this->getInitialPayment();
		$amount = ($this->casco == 1) ? $amount + $this->cascoPrice : $amount;
		return $this->setRoundedValue($amount);
	}

	/**
	 * Возвращает срок кредита, мес
	 * @access public
	 * @return integer срок кредита, мес
	 */
	public function getCreditTime() {
		return $this->creditTime;
	}

	/**
	 * Возвращает размер процентной ставки, %
	 * @access public
	 * @return decimal(65.2) размер процентной ставки, %
	 */
	public function getInterestRate() {
		return $this->interestRate;
	}

	/**
	 * Расчет первого (льготного) платежа, руб
	 * @access public
	 * @return decimal(65.2) первый (льготный) платеж, руб
	 */
	// public function getFirstPayment() {
	// 	$firstPayment = $this->getAmountOfCredit() * $this->getMonthlyPercentages();
	// 	return $this->setRoundedValue($firstPayment);
	// }

	/**
	 * Расчет ежемесячного платежа, руб
	 * @access public
	 * @return float ежемесячный платеж, руб
	 */
	public function getMonthlyPayment() {
		$payment = $this->getAnnuityCoefficient() * $this->getAmountOfCredit();
		return $this->setRoundedValue($payment);		
	}

	public function getCascoPrice() {
		return $this->setRoundedValue($this->cascoPrice);
	}

	/**
	 * Расчет числового коэффициента значения процентной ставки
	 * @access private
	 * @return float числовое значение процентной ставки
	 */
	private function getInterestRateNumeric() {
		return $this->getInterestRate() / self::PERCENTAGES_100;
	}

	/**
	 * Расчет месячной процентной ставки по кредиту
	 * @access private
	 * @return float месячная процентная ставка по кредиту
	 */
	private function getMonthlyPercentages() {
		return $this->getInterestRateNumeric() / self::MONTHS_IN_YEAR;
	}

	/**
	 * Расчет коэффициента аннуитета
	 * @access private
	 * @return float коэффициент аннуитета
	 */
	private function getAnnuityCoefficient() {
		$i = pow(1 + $this->getMonthlyPercentages(), $this->creditTime);
		return ($this->getMonthlyPercentages() * $i) / ($i - 1);
	}

	/**
	 * Расчет стоимости каско, руб
	 * @access private
	 * @return  decimal(65.2), стоимость каско, руб
	 */
	private function setCascoPrice() {
		$cascoPercentages = $this->getCarPrice() * $this->cascoPercentages / self::PERCENTAGES_100;
		return $this->setRoundedValue($cascoPercentages);		
	}
}