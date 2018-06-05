<?php
namespace creditCalc;

require_once 'autoload.php';

$inputUserDataJson = file_get_contents("php://input");

if (strlen($inputUserDataJson) > 0 && SharedValues::isValidJSON($inputUserDataJson)) {
  	$inputUserDataJson = json_decode($inputUserDataJson);

  	if(array_key_exists('carPrice', $inputUserDataJson) && array_key_exists('firstPayment', $inputUserDataJson) && array_key_exists('creditTime', $inputUserDataJson)) {


		$carPrice = intval($inputUserDataJson->carPrice); //2819900
		$firstPaymentPercentage = intval($inputUserDataJson->firstPayment); //30
		$creditTime = intval($inputUserDataJson->creditTime); //36

		$deferred = isset($inputUserDataJson->deferred) && !empty($inputUserDataJson->deferred) ? intval($inputUserDataJson->deferred) : 0; //1
		$deferredPercentages = intval($inputUserDataJson->deferredPayment); //30



		$credit = new CreditData($carPrice, $firstPaymentPercentage, $creditTime, $config->interestRate);

		$casco = new Casco($inputUserDataJson->casco, $config->cascoPercentages);

		$insurance = new Insurance($inputUserDataJson->insurance, $config->insurancePercentages);

		$deferred = new Deferred($deferred, $deferredPercentages);

		$creditCalculator = new CreditCalculator($credit, $casco, $insurance, $deferred);


		$creditData = array(

			/**
			 * Первоначальная стоимость а/м
			 */
			'carPrice' => $creditCalculator->getCarPrice(),

			/**
			 * Сумма кредита (стоимость а/м - первоначальный взнос)
			 */
			'creditAmount' => $creditCalculator->getAmountOfCredit(),

			/**
			 * Размер первоначального взноса, %
			 */
			'initPaymentPercent' => $creditCalculator->getInitialPaymentPercentages(),

			/**
			 * Размер первоначального взноса, руб
			 */
			'initPayment' => $creditCalculator->getInitialPayment(),

			/**
			 * Процентная ставка по кредиту, %
			 */
			'interestRate' => $creditCalculator->getInterestRate(),

			/**
			 * Размер ежемесячного платежа, руб
			 */
			'monthPayment' => $creditCalculator->getMonthlyPayment(),

			/**
			 * Срок кредита
			 */
			'creditTime' => $creditCalculator->getCreditTime(),

			/**
			 * Процентная ставка КАСКО
			 */
			'cascoPercent' => $creditCalculator->getCascoPercentages(),

			/**
			 * Стоимость КАСКО
			 */
			'cascoPrice' => $creditCalculator->getCascoPrice(),

			/**
			 * Процентная ставка страхование жизни
			 */
			'insurancePercent' => $creditCalculator->getInsurancePercentages(),

			/**
			 * Стоимость страхования жизни
			 */
			'insurancePrice' => $creditCalculator->getInsurancePrice(),

			/**
			 * Процентная ставка отложенный платеж
			 */
			'deferredPercent' => $creditCalculator->getDeferredPercentages(),

			/**
			 * Размер отложенного платежа
			 */
			'deferredPayment' => $creditCalculator->getDeferredPaymentPrice(),
		);

		header('Content-Type: application/json');
		echo json_encode($creditData, JSON_UNESCAPED_UNICODE);
		return true;

  	} else {
		echo 'Ошибка. Некорректные входные данные!';
		return false;
  	}

} else {
	echo 'Ошибка. Некорректные входные данные!';
	return false;
}
