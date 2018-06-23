<?php

namespace img\credit_calculator\interfaces;

/**
 * Перечисляет методы, необходимые к реализации во вспомогательных классах (КАСКО, СЖ, Отложенный платеж)
 *
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 */
interface AdditionalPreferencesInterface
{
    /**
     * Возвращает значение необходимости расчета значения
     *
     * @access public
     * @return boolean
     */
    public function isNeedable();

    /**
     * Возвращает значение процентной ставки, рассчитываемой характеристики (КАСКО, СЖ, Отложенный платеж)
     *
     * @access public
     * @return float
     */
    public function getPercentages();

    /**
     * Расчет стоимости рассчитываемой характеристики (КАСКО, СЖ, Отложенный платеж)
     *
     * @param $argument float вспомогательный аргумент для расчета стоимости (КАСКО и отложенный платеж - стоимость а/м, СЖ - сумма кредита
     * @access public
     * @return float
     */
    public function setPrice($argument);
}