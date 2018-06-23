<?php

namespace img\credit_calculator\services;

use img\credit_calculator\interfaces\AdditionalPreferencesInterface;
use img\credit_calculator\base\Base;
use img\credit_calculator\base\Booleans;

/**
 * Вспомогательный класс, предназначенный для передачи предварительно рассчитанных значений расчета КАСКО кредитному калькулятор у КАСКО расчитывается по принципу получения процентной ставки на стоимость а/м.
 * Стоимость КАСКО учитывается при расчете ежемесячного платежа <br>
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 * @final
 */
final class Casco implements AdditionalPreferencesInterface
{
	/**
	 * Необходимость расчета КАСКО
	 * 
	 * @access private
	 * @var bool
	 */
	private $needCasco;

	/**
	 * Процентная ставка КАСКО
	 * @access private
	 * @var float
	 */
	private $cascoPercentages;

    /**
     * Присваивает переданные классу при инициализации аргументы приватным свойствам
     *
     * @param bool|int $needCasco необходимость учета КАСКО
     * @param float $cascoPercentages процентная ставка КАСКО
     * @throws \InvalidArgumentException
     */
	function __construct($needCasco = 0, $cascoPercentages = null)
	{
        Base::validateServiceInput($needCasco, $cascoPercentages);
		$this->needCasco = Booleans::setBooleanValue($needCasco);
		$this->cascoPercentages = $cascoPercentages;
	}

	/**
	 * Возвращает необходимость учета каско
	 * 
	 * @access public
	 * @return boolean необходимость учета каско
	 */
    public function isNeedable() {
		return $this->needCasco;
	}

	/**
	 * Возвращает размер процентной ставки КАСКО
	 * @access public
	 * @return float  размер процентной ставки КАСКО, %
	 */
    public function getPercentages() {
		return $this->cascoPercentages;
	}

	/**
	 * Устанавливает значение стоимости КАСКО
	 * 
	 * ```
	 * СК = СА * ПСК / 100
	 * где:
	 * СК - стоимость КАСКО,
	 * СА - стоимость а/м, руб,
	 * ПСК - Процентная ставка КАСКО, %
	 * ```
	 * 
	 * @access public
	 * @param float $carPrice итоговая стоимость а/м
	 * @return  float | 0 сумма затрат на оплату КАСКО
	 */
    public function setPrice($carPrice) {
		return ($this->needCasco) ? $carPrice * $this->cascoPercentages / Base::PERCENTAGES_100 : 0;
	}
}