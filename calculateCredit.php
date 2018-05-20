<?php
namespace creditCalc;

require_once 'autoload.php';
$config = include('config.inc.php');

if(isset($_POST['carPrice']) && isset($_POST['firstPayment']) && $_POST['creditTime']){


	$carPrice = intval($_POST['carPrice']);
	$firstPaymentPercentage = intval($_POST['firstPayment']);
	$creditTime = intval($_POST['creditTime']);
	$casco = isset($_POST['casco']) && !empty($_POST['casco']) ? $_POST['casco'] : 0;
	$insurance = isset($_POST['insurance']) && !empty($_POST['insurance']) ? $_POST['insurance'] : 0;


	$creditCalculator = new CreditCalculator(
			$carPrice,
			$firstPaymentPercentage,
			$creditTime,
			$config->interestRate,
			$casco,
			$config->cascoPercentages,
			$insurance,
			$config->insurancePercentages
		);

	$creditCalculator->roundCoefficient = 0;

	$creditData = array(
			'carPrice' => $creditCalculator->getCarPrice(),
			'creditAmount' => $creditCalculator->getAmountOfCredit(),
			'creditTime' => $creditCalculator->getCreditTime(),
			'initialPayment' => $creditCalculator->getInitialPayment(),
			'initialPaymentPercentages' => $creditCalculator->getInitialPaymentPercentages(),
			'interestRate' => $creditCalculator->getInterestRate(),
			'monthlyPayment' => $creditCalculator->getMonthlyPayment(),
			'cascoPercentages' => $creditCalculator->getCascoPercentages(),
			'cascoPrice' => $creditCalculator->getCascoPrice(),
			'insurancePercentages' => $creditCalculator->getInsurancePercentages(),
			'insurancePrice' => $creditCalculator->getInsurancePrice(),


		);

	$creditData = json_encode($creditData);

	print_r($creditData);

} else {
	throw new \Exception("Неверный метод запроса", 1);
	
}