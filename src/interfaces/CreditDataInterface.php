<?php

namespace img\credit_calculator\interfaces;

/**
 * Перечисляет методы, необходимые для получения сведений об условиях кредита
 *
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  maximishchenko/credit_calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 */
interface CreditDataInterface
{
    /**
     * Возвращает стоимость а/м, руб.
     *
     * @access public
     * @return float
     */
    public function getCarPrice();

    /**
     * Возвращает размер первоначального взноса, %
     *
     * @access public
     * @return float
     */
    public function getFirstPaymentPercentages();

    /**
     * Возвращает срок кредитования, мес
     *
     * @access public
     * @return integer
     */
    public function getCreditTime();

    public function getInterestRate();
}