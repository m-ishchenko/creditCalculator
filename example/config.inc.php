<?php


/**
 * Указание параметров по-умолчанию
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 */
return (object) array(

	/**
	 * Учитывать каско
	 */
    'casco' => 0,

    /**
     * Кредитная ставка
     */
    'interestRate' => 10.9, //14.8

    /**
     * Процент для расчета каско
     */
    'cascoPercentages' => 0.71,

    /**
     * Учитывать страхование жизни
     */
    'insurance' => 0,

    /**
     * Процент для расчета страхования
     */
    'insurancePercentages' => 17,

    /**
     * Минимально-допустиная стоимость а/м для расчета
     */
    'minCreditPrice' => 500000,

    /**
     * % от стоимости а/м для расчета первоначального взноса
     */
    'firstPaymentArray' => array(30, 60, 90),

    /**
     * срок кредита, мес
     */
    'creditTimeArray' => array(24, 36, 48, 60),

    /**
     * % от стоимости а/м для расчета отложенного платежа
     */
    'deferredPaymentArray' => array(20, 30, 40),
);

?>