<?php
namespace img\credit_calculator;

/**
 * Вспомогательный класс, предназначенный для передачи предварительно рассчитанных значений расчета КАСКО кредитному калькулятор у КАСКО расчитывается по принципу получения процентной ставки на стоимость а/м.
 * Стоимость КАСКО учитывается при расчете ежемесячного платежа <br>
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
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
	 * @param boolean $needCasco        необходимость учета КАСКО
	 * @param float $cascoPercentages процентная ставка КАСКО
	 */
	function __construct($needCasco = 0, $cascoPercentages = null)
	{
		try {
//			if(Base::checkIsNull($needCasco)) {
//				Base::validateNumbers($needCasco, Base::BOOLEAN_VALIDATOR);
//			}
			if(Base::checkIsNull($cascoPercentages)) {
				Base::validateNumbers($cascoPercentages, Base::FLOAT_VALIDATOR);
			}	
		} catch (Exception $e) {
			print('Ошибка валидации: ' .$e->getMessage());
		}

		$this->needCasco = $needCasco;
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
	 * @return float  размер процентной ставки КАСКО, руб
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
	 * @return  float сумма затрат на оплату КАСКО
	 */
	public function setCascoPrice($carPrice) {
		return $carPrice * $this->cascoPercentages / Base::PERCENTAGES_100;
	}
}