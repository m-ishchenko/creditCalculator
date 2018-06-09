<?php
namespace maximishchenko\credit_calculator;

/**
 * Вспомогательный класс, предназначенный для передачи значений условий кредита калькулятору
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 * @final
 */
final class CreditData
{
	/**
	 * Стоимость а/м
	 * 
	 * @access  private
	 * @var float
	 */
	private $carPrice;

	/**
	 * Размер первоначального взноса, %
	 * 
	 * @access  private
	 * @var float
	 */
	private $firstPaymentPercentage;

	/**
	 * Срок кредита,мес
	 * 
	 * @access  private
	 * @var integer
	 */
	private $creditTime;

	/**
	 * Процентная ставка по кредиту, %
	 * 
	 * @access  private
	 * @var float
	 */
	private $interestRate;

	/**
	 * Присваивает переданные классу при инициализации аргументы приватным свойствам
	 * 
	 * @param float $carPrice               итоговая стоимость а/м, руб
	 * @param integer $firstPaymentPercentage размер первоначального взноса, %
	 * @param integer $creditTime             срок кредитования, мес
	 * @param float $interestRate           процентная ставка по кредиту, %
	 */
	function __construct($carPrice, $firstPaymentPercentage, $creditTime, $interestRate)
	{
		try {
			Base::validateNumbers($carPrice, Base::FLOAT_VALIDATOR);
			Base::validateNumbers($firstPaymentPercentage, Base::FLOAT_VALIDATOR);
			Base::validateNumbers($creditTime, Base::INT_VALIDATOR);
			Base::validateNumbers($interestRate, Base::FLOAT_VALIDATOR);
		} catch (Exception $e) {
			print('Ошибка валидации: ' .$e->getMessage());
		}

		$this->carPrice = $carPrice;
		$this->firstPaymentPercentage = $firstPaymentPercentage;
		$this->creditTime = $creditTime;
		$this->interestRate = $interestRate;
	}

	/**
	 * Возвращает стоимость а/м
	 * 
	 * @access public
	 * @return float стоимость а/м, руб
	 */
	public function getCarPrice() {
		return $this->carPrice;
	}

	/**
	 * Возвращает размер первоначального взноса, %
	 * 
	 * @access  public
	 * @return integer первоначальный взнос, руб
	 */
	public function getFirstPaymentPercentages() {
		return $this->firstPaymentPercentage;
	}

	/**
	 * Возвращает срок кредитования
	 * 
	 * @access  public
	 * @return integer срок кредита, мес
	 */
	public function getCreditTime() {
		return $this->creditTime;
	}

	/**
	 * Возвращает размер процентной ставки по кредиту
	 * 
	 * @access  public
	 * @return float процентная ставка по кредиту, %
	 */
	public function getInterestRate() {
		return $this->interestRate;
	}
}