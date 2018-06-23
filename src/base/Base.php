<?php

namespace img\credit_calculator\base;

use img\credit_calculator\interfaces\AdditionalPreferencesInterface;

/**
 * Содержит значения, справедливые для всех объектов
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
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
     * @return float
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
     * @return bool возвращает истину, если переданный тип валидатора присутствует среди ключей массива self::getAvailableValidationFiltersTypesArray()
     * @throws \Exception* @see self::getAvailableValidationFiltersTypesArray()
     */
	public static function validateFilterTypes($filterType) {
		if(in_array($filterType, array_keys( self::getAvailableValidationFiltersTypesArray() ))) {
			return true;		
		} else {
			throw new \Exception("Некорректный валидатор");	
		}
	}

    /**
     * осуществляет валидацию, передаваемых числовых значений
     *
     * @access  public
     * @static
     * @param  int|float|boolean $arg числовой аргумент, подлежащий валидации
     * @param  string $filterType тип, используемого фильтра
     * @return bool возвращает истину, если переданный аргумент валиден, ложь - в обратном случае
     * @see self::getAvailableValidationFiltersTypesArray()
     */
	public static function validateNumbers($arg, $filterType) {
		if(self::validateFilterTypes($filterType)) {
			$availableFilters = self::getAvailableValidationFiltersTypesArray();
			return (filter_var(trim($arg), $availableFilters[$filterType], FILTER_NULL_ON_FAILURE)) ? true : false;
		}
		return false;
	}


    /**
     * @param AdditionalPreferencesInterface $needable
     * @param AdditionalPreferencesInterface $percentages
     */
    public static function validateServiceInput($needable, $percentages) {

        if(Base::checkIsNull($needable) && Base::checkIsNull($percentages)){
            if(!Base::validateNumbers($percentages, Base::FLOAT_VALIDATOR)) {
                throw new \InvalidArgumentException('Размер процентной ставки должен быть целым числом');
            }
        } else {
            throw new \InvalidArgumentException('Переданы пустые значения');
        }
    }
}