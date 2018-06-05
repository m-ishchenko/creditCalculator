<?php

namespace creditCalc;

/**
 * Содержит значения, справедливые для всех объектов
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 */
class SharedValues
{

	/**
	 * Числовое значение 100%
	 */
	const PERCENTAGES_100 = 100;

	/**
	 * Числовое значение - количество месяцев в году
	 */
	const MONTHS_IN_YEAR = 12;

	/**
	 * Количество символов округления
	 */
	const ROUND_COEFFICIENT = 0;
	
	/**
	 * округляет переданное в качестве аргумента значение, до кол-ва, указанного в константе ROUND_COEFFICIENT
	 * 
	 * @access  public
	 * @param float $value округленное значение
	 */
	public static function setRoundedValue($value) {
		return round($value, self::ROUND_COEFFICIENT);
	}

	/**
	 * валидирует JSON-объект
	 * @param  string  $str JSON-строка
	 * @return boolean      результат валидации JSON-строки
	 */
	public static function isValidJSON($str) {
	   json_decode($str);
	   return json_last_error() == JSON_ERROR_NONE;
	}

}