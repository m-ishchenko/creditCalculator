<?php
namespace CreditCalculator;

/**
 * Содержит значения, справедливые для всех объектов
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 */
class Base
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
	 * Значение валидатора integer
	 */
	const INT_VALIDATOR = 'int';

	/**
	 * Значение валидатора float
	 */
	const FLOAT_VALIDATOR = 'float';

	/**
	 * Значение валидатора boolean
	 */
	const BOOLEAN_VALIDATOR = 'boolean';
	
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
	 * проверяет, является ли переданное значение null
	 * 
	 * @access  public
	 * @static
	 * @param  string $value переданное значение
	 * @return boolean        истина, если null, ложь - в противном случае
	 */
	public static function checkIsNull($value) {
		return ($value !== null) ? true : false;
	}

	/**
	 * возвращает масив доступных типов валидаторов, сгруппированный по ключам констант-валидаторов
	 * 
	 * @access  public
	 * @static
	 * @return array масив доступных типов валидаторов
	 */
	public static function getAvailableValidationFiltersTypesArray() {
		return array(
				self::INT_VALIDATOR => FILTER_VALIDATE_INT,
				self::FLOAT_VALIDATOR => FILTER_VALIDATE_FLOAT,
				self::BOOLEAN_VALIDATOR => FILTER_VALIDATE_BOOLEAN
			);
	}

	/**
	 * проверяет корректность переданного типа валидатора
	 * 
	 * @access  public
	 * @static
	 * @param  string $filterType тип фильтра валидатора
	 * @return boolean             возвращает истину, если переданный тип валидатора присутствует среди ключей массива self::getAvailableValidationFiltersTypesArray()
	 * @see self::getAvailableValidationFiltersTypesArray()
	 * @throws Exception исключение, если валидатора нет среди ключей массива, полученного в результате выполнения метода self::getAvailableValidationFiltersTypesArray()
	 */
	public static function validateFilterTypes($filterType) {
		if(in_array($filterType, array_keys( self::getAvailableValidationFiltersTypesArray() ))) {
			return true;		
		} else {
			throw new \Exception("Некорректный валидатор");	
		}
		return false;
	}

	/**
	 * осуществляет валидацию, передаваемых числовых значений
	 * 
	 * @access  public
	 * @static
	 * @param  int|float|boolean $arg        числовой аргумент, подлежащий валидации
	 * @param  string $filterType тип, используемого фильтра
	 * @return boolean             возвращает истину, если переданный аргумент валиден, ложь - в обратном случае
	 * @see self::validateFilterTypes()
	 * @see self::getAvailableValidationFiltersTypesArray()
	 * @throws Exception бросает исключение, если переданное значение не валидно
	 */
	public static function validateNumbers($arg, $filterType) {
		if(self::validateFilterTypes($filterType)) {
			$availableFilters = self::getAvailableValidationFiltersTypesArray();
			if(filter_var(trim($arg), $availableFilters[$filterType])) {
				return true;
			} else {
				throw new \Exception("Ошибка валидации значения ".$arg);
			}
			return false;
		}
		return false;
	}
}