<?php
namespace img\credit_calculator;
use img\credit_calculator\Base;
use img\credit_calculator\Booleans;

/**
 * Вспомогательный класс, предназначенный для передачи предварительно рассчитанных значений расчета КАСКО кредитному калькулятор у КАСКО расчитывается по принципу получения процентной ставки на стоимость а/м.
 * Стоимость КАСКО учитывается при расчете ежемесячного платежа <br>
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://www.gnu.org/licenses/gpl-3.0.ru.html
 * @version 1.1
 * @final
 */
final class Casco
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
        if(Base::checkIsNull($cascoPercentages)) {
            if(!Base::validateNumbers($cascoPercentages, Base::FLOAT_VALIDATOR)) {
                throw new \InvalidArgumentException('Процентная ставка КАСКО должна быть числом');
            }
		} else {
			throw new \InvalidArgumentException('КАСКО. Переданы пустые значения');
        }

		$this->needCasco = Booleans::setBooleanValue($needCasco);
		$this->cascoPercentages = $cascoPercentages;
	}

	/**
	 * Возвращает необходимость учета каско
	 * 
	 * @access public
	 * @return boolean необходимость учета каско
	 */
	public function isNeedCasco() {
		return $this->needCasco;
	}

	/**
	 * Возвращает размер процентной ставки КАСКО
	 * @access public
	 * @return float  размер процентной ставки КАСКО, %
	 */
	public function getCascoPercentages() {
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
	public function setCascoPrice($carPrice) {
		return ($this->needCasco) ? $carPrice * $this->cascoPercentages / Base::PERCENTAGES_100 : 0;
	}
}