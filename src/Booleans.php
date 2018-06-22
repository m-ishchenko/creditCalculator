<?php

namespace img\credit_calculator;


class Booleans
{
    const TRUE_VALUE_ONE = 1;
    const TRUE_VALUE_TRUE = 'true';
    const TRUE_VALUE_ON = 'on';
    const FALSE_VALUE_ZERO = 0;
    const FALSE_VALUE_FALSE = 'false';
    const FALSE_VALUE_OFF = 'off';

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

    protected static function getAvailableValuesString()
    {
        return implode(', ', static::getAvailableValuesArray());
    }

    protected static function getTrueValuesArray()
    {
        return [
            static::TRUE_VALUE_ONE,
            static::TRUE_VALUE_TRUE,
            static::TRUE_VALUE_ON
        ];
    }

    protected static function getFalseValuesArray()
    {
        return [
            static::FALSE_VALUE_ZERO,
            static::FALSE_VALUE_FALSE,
            static::FALSE_VALUE_OFF
        ];
    }

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
}