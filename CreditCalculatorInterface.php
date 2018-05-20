<?php
namespace creditCalc;

/**
 * @author  Maxim Ishchenko <maxim.ishchenko@gmail.com>
 */
interface CreditCalculatorInterface {

	/**
	 * Стоимость а/м, руб
	 * @return decimal(65.2) стоимость а/м, руб
	 */
	public function getCarPrice();

	/**
	 * Расчет первоначального взноса, руб
	 * @return decimal(65.2) первоначальный взнос, руб
	 */
	public function getInitialPayment();

	/**
	 * Расчет суммы кредита, руб
	 * @return decimal(65.2) сумма кредита, руб
	 */
	public function getAmountOfCredit();

	/**
	 * Расчет первоначального взноса, %
	 * @return integer первоначальный взнос, %
	 */
	public function getInitialPaymentPercentages();

	/**
	 * Срок кредита, мес
	 * @return integer срок кредита, мес
	 */
	public function getCreditTime();

	/**
	 * Процентная ставка, %
	 * @return integer процентная ставка, %
	 */
	public function getInterestRate();

	/**
	 * Расчет первого (льготного) платежа, руб
	 * @return decimal(65.2) первый (льготный) платеж, руб
	 */
	// public function getFirstPayment();

	/**
	 * Расчет ежемесячного платежа, руб.
	 * @return float ежемесячный платеж, руб.
	 */
	public function getMonthlyPayment();

	/**
	 * Расчет стоимости каско, руб
	 * @return decimal(65.2) расчет стоимости каско, руб
	 */
	public function getCascoPrice();

	/**
	 * Расчет стоимости страхования жизни, руб
	 * @return decimal(65.2) расчет стоимости страхования жизни, руб
	 */
	public function getInsurancePrice();

	/**
	 * Возвращает процентную ставку расчета КАСКО, %
	 * @return integer процентная ставка расчета КАСКО, %
	 */
	public function getCascoPercentages();

	/**
	 * Возвращает процентную ставку расчета страхования жизни, %
	 * @return integer процентная ставка страхования жизни, %
	 */
	public function getInsurancePercentages();

	/**
	 * Расчет стоимости остаточного платежа, руб.
	 * @return decimal(65.2) стоимость остаточного платежа, руб.
	 */
	public function getDeferredPayentPrice();
}