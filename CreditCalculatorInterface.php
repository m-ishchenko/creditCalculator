<?php
namespace creditCalc;

/**
 * 
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
}