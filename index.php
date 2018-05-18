<?php
namespace creditCalc;

require_once 'autoload.php';

$carPrice = 2819900;
$firstPaymentPercentage = 30;
$creditTime = 36;
$interestRate = 14.8;
$casco = 1;
$cascoPercentages = 0.71;

/**
 * @todo реализовать передачу аргуметов средствами DTO
 */
$creditCalculator = new CreditCalculator(
		$carPrice,
		$firstPaymentPercentage,
		$creditTime,
		$interestRate,
		$casco,
		$cascoPercentages
	);


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

echo '<br/>Стоимость КАСКО, руб.: ';
echo $creditCalculator->getCascoPrice();


//1973930