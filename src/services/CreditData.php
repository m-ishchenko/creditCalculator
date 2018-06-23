<?php

namespace img\credit_calculator\services;

use img\credit_calculator\interfaces\CreditDataInterface;
use img\credit_calculator\base\Base;

/**
 * Вспомогательный класс, предназначенный для передачи значений условий кредита калькулятору
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 */
class CreditData implements CreditDataInterface
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
     * @throws \InvalidArgumentException бросает исключение, если переданы некорректные значения
     * @todo Пересмотреть валидацию аргументов конструктора
	 */
	function __construct($carPrice, $firstPaymentPercentage, $creditTime, $interestRate)
	{
	    $this->validateInput($carPrice, $firstPaymentPercentage, $creditTime, $interestRate);
		$this->carPrice = $carPrice;
		$this->firstPaymentPercentage = $firstPaymentPercentage;
		$this->creditTime = $creditTime;
		$this->interestRate = $interestRate;
	}

    /**
     * Валидация входных параметров
     *
     * @param $carPrice
     * @param $firstPaymentPercentage
     * @param $creditTime
     * @param $interestRate
     */
	private function validateInput($carPrice, $firstPaymentPercentage, $creditTime, $interestRate) {
        if(!Base::validateNumbers($carPrice, Base::FLOAT_VALIDATOR)) {
            throw new \InvalidArgumentException('Ошибка валидации передаваемого значения');
        }
        if(!Base::validateNumbers($firstPaymentPercentage, Base::FLOAT_VALIDATOR)) {
            throw new \InvalidArgumentException('Ошибка валидации передаваемого значения');
        }
        if(!Base::validateNumbers($creditTime, Base::FLOAT_VALIDATOR)) {
            throw new \InvalidArgumentException('Ошибка валидации передаваемого значения');
        }
        if(!Base::validateNumbers($interestRate, Base::FLOAT_VALIDATOR)) {
            throw new \InvalidArgumentException('Ошибка валидации передаваемого значения');
        }
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