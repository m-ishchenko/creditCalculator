<?php
namespace creditCalc;

require_once 'autoload.php';

/**
 * Чтение файла конфигурации
 * @var object
 */
$config = include('config.inc.php');

if($config->debug) {
	error_reporting(0);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(E_ALL);
	ini_set("error_reporting", E_ALL);
	error_reporting(E_ALL & ~E_NOTICE);
}
?>

<html>
	<head>
		<title>Калькулятор расчета автокредита</title>
		<link rel="stylesheet" href="style.css">
	</head>

	<body>

	<h1>Калькулятор расчета автокредита</h1>

	<hr>

	<div class="credit-data" id="credit-data">
		<form id="credit-user-data" action="/" method="get">
		<!-- <form id="credit-user-data" action="/calculateCredit.php" method="post"> -->

			<table>
				<tr>
					<td>						
						<label for="carPrice">Стоимость а/м, &#8381;</label>
					</td>
					<td>
						<input type="number" name="carPrice" id="carPrice" required value="<?php echo isset($_GET['carPrice']) && !empty($_GET['carPrice']) ? $_GET['carPrice'] : null?>" min="<?php echo intval($config->minCreditPrice); ?>"><br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="firstPayment">Первоначальный взнос, %</label>
					</td>
					<td>						
						<select name="firstPayment" id="firstPayment" required>
							<?php foreach ($config->firstPaymentArray as $key => $firstPaymentValue): ?>
								<?php $isPaymentSelected = ($firstPaymentValue == $_GET['firstPayment']) ? 'selected' : 0; ?>
								<option value="<?php echo $firstPaymentValue; ?>" <?php echo $isPaymentSelected; ?>><?php echo $firstPaymentValue; ?>%</option>
							<?php endforeach; ?>

						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="creditTime">Срок кредита, мес.</label>
					</td>
					<td>						
						<select name="creditTime" id="creditTime" required>
							<?php foreach ($config->creditTimeArray as $key => $creditTimeValue) : ?>
								<?php $isTimeSelected = ($creditTimeValue == $_GET['creditTime']) ? 'selected' : 0; ?>
								<option value="<?php echo $creditTimeValue; ?>" <?php echo $isTimeSelected; ?> > <?php echo $creditTimeValue; ?> мес.</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="deferred">Отложенный платеж</label>
					</td>
					<td>
						<?php
							$isDeferredChecked = (isset($_GET['deferred']) && $_GET['deferred'] == 1) ? true : false;
							$isDeferredVisible = (isset($_GET['deferred']) && $_GET['deferred'] == 1) ? 'visibility: unset' : 'visibility: hidden';
						?>
						<input type="checkbox" name="deferred" class="deferred" id="deferred" value="1" <?php echo ($isDeferredChecked) ? 'checked' : 0; ?> onclick="toggleByCheckbox('deferred', 'deferredPayment');">


						<select name="deferredPayment" id="deferredPayment" style="<?php echo $isDeferredVisible; ?>" required>
							<?php foreach ($config->deferredPaymentArray as $key => $deferredPaymentValue) : ?>
								<?php $deferredSelected = ($deferredPaymentValue == $_GET['deferredPayment']) ? 'selected' : 0; ?>
								<option value="<?php echo $deferredPaymentValue; ?>" <?php echo $deferredSelected; ?> > <?php echo $deferredPaymentValue; ?> %</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<td>
						<label for="casco">КАСКО</label>
					</td>
					<td>						
						<?php $isCascoChecked = (isset($_GET['casco']) && $_GET['casco'] == 1) ? true : false; 			?>
						<input type="checkbox" name="casco" class="casco" id="casco" value="1" <?php echo ($isCascoChecked) ? 'checked' : 0; ?>>
					</td>
				</tr>
				<tr>
					<td>
						<label for="insurance">Страхование жизни</label>
					</td>
					<td>
						<?php $isInsuranceChecked = (isset($_GET['insurance']) && $_GET['insurance'] == 1) ? true : false; 			?>
						<input type="checkbox" name="insurance" class="insurance" id="insurance" value="1" <?php echo ($isInsuranceChecked) ? 'checked' : 0; ?>>
					</td>
				</tr>

			</table>

			<input type="submit" value="Рассчитать" class="submit" id="submit">
			<a href="/">Сбросить</a>

		</form>
	</div>

	<?php

	if (isset($_GET['carPrice']) && isset($_GET['firstPayment']) && isset($_GET['creditTime']) ):


	$carPrice = intval($_GET['carPrice']); //2819900
	$firstPaymentPercentage = intval($_GET['firstPayment']); //30
	$creditTime = intval($_GET['creditTime']); //36
	$casco = isset($_GET['casco']) && !empty($_GET['casco']) ? $_GET['casco'] : 0; //1
	$insurance = isset($_GET['insurance']) && !empty($_GET['insurance']) ? $_GET['insurance'] : 0; //1
	$deferred = isset($_GET['deferred']) && !empty($_GET['deferred']) ? intval($_GET['deferred']) : 0; //1
	$deferredPercentages = intval($_GET['deferredPayment']); //30



	$creditCalculator = new CreditCalculator(
			$carPrice,
			$firstPaymentPercentage,
			$creditTime,
			$config->interestRate,
			$casco,
			$config->cascoPercentages,
			$insurance,
			$config->insurancePercentages,
			$deferred,
			$deferredPercentages
		);

	$creditCalculator->roundCoefficient = 0;

	?>

	<div class="credit-values">


		<table class="credit-table" id="printableArea">
			<thead>
				<th>
					<td colspan="8">
						<h2>Стоимость а/м <?php echo $creditCalculator->getCarPrice(); ?>  &#8381;</h2>
					</td>
				</th>
			</thead>
			<tbody>
				<tr>
					<td>#</td>
					<td colspan="2">Условия кредитования</td>
					<td colspan="2">КАСКО</td>
					<td colspan="2">Страхование жизни</td>
					<td colspan="2">Отложенный платеж</td>
				</tr>
				<tr>
					<td>1</td>
					<td>
						Сумма кредита
					</td>
					<td>
						<?php echo $creditCalculator->getAmountOfCredit(); ?>  &#8381;
					</td>
					<td>
						Процентная ставка
					</td>
					<td>
						<?php echo $creditCalculator->getCascoPercentages(); ?>  %
					</td>
					<td>
						Процентная ставка
					</td>
					<td>
						<?php echo $creditCalculator->getInsurancePercentages(); ?> %
					</td>
					<td>
						Процентная ставка
					</td>
					<td>
						<?php echo $creditCalculator->getDeferredPercentages(); ?> %						
					</td>
				</tr>
				<tr>
					<td>2</td>
					<td>
						Срок кредита
					</td>
					<td>
						<?php echo $creditCalculator->getCreditTime(); ?> мес
					</td>
					<td>
						Стоимость КАСКО
					</td>
					<td>
						<?php echo $creditCalculator->getCascoPrice(); ?>  &#8381;
					</td>
					<td>
						Стоимость СЖ
					</td>
					<td>
						<?php echo $creditCalculator->getInsurancePrice(); ?> &#8381;
					</td>
					<td>
						Размер отложенного платежа
					</td>
					<td>
						<?php echo $creditCalculator->getDeferredPaymentPrice(); ?> &#8381;
					</td>
				</tr>
				<tr>
					<td>3</td>
					<td>
						Первоначальный взнос
					</td>
					<td>
						<?php echo $creditCalculator->getInitialPayment() . '  &#8381; (' . $creditCalculator->getInitialPaymentPercentages() . '%)'; ?>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>4</td>
					<td>
						Процентная ставка
					</td>
					<td>
						<?php echo $creditCalculator->getInterestRate(); ?> %
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>5</td>
					<td>
						Ежемесячный платеж
					</td>
					<td>
						<?php echo $creditCalculator->getMonthlyPayment(); ?>  &#8381;
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
			
		</table>
		


		<input type="button" value="Печать" id="print" style="margin-left: 15px;">


	</div>

	<?php endif; ?>

	<!-- <hr> -->
		
	</body>
</html>

<script type="text/javascript" src="main.js"></script>