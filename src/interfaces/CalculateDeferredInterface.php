<?php

namespace img\credit_calculator\interfaces;

/**
 * Перечисляет методы необходимые только для расчета отложенного платежа
 *
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 */
interface CalculateDeferredInterface
{
    /**
     * Расчет суммы процентов отложенного платежа для уплаты кредита
     *
     * @param $carPrice float стоимость а/м, руб.
     * @param $interestRate float процент отложенного платежа, %
     * @access public
     * @return float
     */
    public function setDeferredPercentagesPrice($carPrice, $interestRate);
}