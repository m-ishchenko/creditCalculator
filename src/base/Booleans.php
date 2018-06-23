<?php

namespace img\credit_calculator\base;

/**
 * Приведение булевых значений
 *
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 */
class Booleans
{
    /**
     * Значение "Истина" - 1
     */
    const TRUE_VALUE_ONE = 1;

    /**
     * Значение "Истина" - true
     */
    const TRUE_VALUE_TRUE = 'true';

    /**
     * Значение "Истина" - on
     */
    const TRUE_VALUE_ON = 'on';

    /**
     * Значение "Ложь" - 0
     */
    const FALSE_VALUE_ZERO = 0;

    /**
     * Значение "Ложь" - false
     */
    const FALSE_VALUE_FALSE = 'false';

    /**
     * Значение "Ложь" - off
     */
    const FALSE_VALUE_OFF = 'off';

    /**
     * Приводит переданное значение к true|false
     *
     * @param $value    приводимое значение
     * @throws \InvalidArgumentException бросает исключение, если значение не присутствует в массиве, возвращаемом методом  getAvailableValuesArray()
     * @return bool
     */
    public static function setBooleanValue($value)
    {
        if(in_array($value, static::getTrueValuesArray())) {
            $value = true;
        } elseif(in_array($value, static::getFalseValuesArray())) {
            $value = false;
        } else {
            throw new \InvalidArgumentException('Передано некорректное значение. Ожидается одно из следующих значений: ' . static::getAvailableValuesString());
        }
        return $value;
    }

    /**
     * Возвращает массив допустимых значений
     *
     * @access protected
     * @return array
     */
    protected static function getAvailableValuesArray()
    {
        return [
            static::TRUE_VALUE_ONE,
            static::TRUE_VALUE_TRUE,
            static::TRUE_VALUE_ON,
            static::FALSE_VALUE_ZERO,
            static::FALSE_VALUE_FALSE,
            static::FALSE_VALUE_OFF
        ];
    }

    /**
     * Возвращает строку допустимых значений
     *
     * @access protected
     * @return string
     */
    protected static function getAvailableValuesString()
    {
        return implode(', ', static::getAvailableValuesArray());
    }

    /**
     * Возвращает массив допустимых значений для валидации значения "Истина"
     *
     * @access protected
     * @return array
     */
    protected static function getTrueValuesArray()
    {
        return [
            static::TRUE_VALUE_ONE,
            static::TRUE_VALUE_TRUE,
            static::TRUE_VALUE_ON
        ];
    }

    /**
     * Возвращает массив допустимых значений для валидации значения "Ложь"
     *
     * @access protected
     * @return array
     */
    protected static function getFalseValuesArray()
    {
        return [
            static::FALSE_VALUE_ZERO,
            static::FALSE_VALUE_FALSE,
            static::FALSE_VALUE_OFF
        ];
    }
}