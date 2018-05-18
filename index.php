<?php
namespace creditCalc;

require_once 'autoload.php';

$carPrice = 2819900;
$firstPaymentPercentage = 30;
$creditTime = 36;
$interestRate = 14.8;

/**
 * @todo реализовать передачу аргуметов средствами DTO
 */
$creditCalculator = new CreditCalculator(
		$carPrice,
		$firstPaymentPercentage,
		$creditTime,
		$interestRate
	);

$creditCalculator->casco = 1;


echo 'Стоимость а/м, руб: ';
echo $creditCalculator->getCarPrice();

echo '<br/>Первоначальный взнос, руб (%): ';
echo $creditCalculator->getInitialPayment() . ' (' . $creditCalculator->getInitialPaymentPercentages() . '%)';

echo '<br/>Сумма кредита, руб.: ';
echo $creditCalculator->getAmountOfCredit();

echo '<br/>Срок кредита, мес.: ';
echo $creditCalculator->getCreditTime();

echo '<br/>Процентная ставка, %: ';
echo $creditCalculator->getInterestRate();

// echo '<br/>Первый платеж, руб.: ';
// echo $creditCalculator->getFirstPayment();

echo '<br/>Ежемесячный платеж, руб.: ';
echo $creditCalculator->getMonthlyPayment();
