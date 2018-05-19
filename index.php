<?php
namespace creditCalc;

require_once 'autoload.php';


/**
 * Значения формы по-умолчанию
 * @var DefaultValues
 */
$defaultValues = new DefaultValues();
?>

<html>
	<head>
		<title>Калькулятор расчета автокредита</title>
		<link rel="stylesheet" href="style.css">
	</head>

	<body>

	<h1>Калькулятор расчета автокредита</h1>

	<div class="credit-data" id="credit-data">
		<form action="#">

			<label for="carPrice">Стоимость а/м, &#8381;</label>
			<input type="number" name="carPrice" id="carPrice" required value="<?php echo isset($_GET['carPrice']) && !empty($_GET['carPrice']) ? $_GET['carPrice'] : null?>"><br>
			
			<label for="firstPayment">Первоначальный взнос, %</label>
			<select name="firstPayment" id="firstPayment" required>
				<?php foreach ($defaultValues->firstPaymentArray as $key => $firstPaymentValue): ?>
					<?php $isPaymentSelected = ($firstPaymentValue == $_GET['firstPayment']) ? 'selected' : 0; ?>
					<option value="<?php echo $firstPaymentValue; ?>" <?php echo $isPaymentSelected; ?>><?php echo $firstPaymentValue; ?>%</option>
				<?php endforeach; ?>

			</select><br>

			<label for="creditTime">Срок кредита, мес.</label>
			<select name="creditTime" id="creditTime" required>
				<?php foreach ($defaultValues->creditTimeArray as $key => $creditTimeValue) : ?>
					<?php $isTimeSelected = ($creditTimeValue == $_GET['creditTime']) ? 'selected' : 0; ?>
					<option value="<?php echo $creditTimeValue; ?>" <?php echo $isTimeSelected; ?> > <?php echo $creditTimeValue; ?> мес.</option>
				<?php endforeach; ?>
			</select><br>

			<label for="casco">КАСКО</label>
			<?php $isCascoChecked = (isset($_GET['casco']) && $_GET['casco'] == 1) ? true : false; 			?>
			<input type="checkbox" name="casco" class="casco" id="casco" value="1" <?php echo ($isCascoChecked) ? 'checked' : 0; ?>>
			<br>

			<!-- <hr> -->
			<input type="submit" value="Рассчитать">

		</form>
	</div>

	<?php

	if (isset($_GET['carPrice']) && isset($_GET['firstPayment']) && isset($_GET['creditTime']) ):


	$carPrice = intval($_GET['carPrice']); //2819900
	$firstPaymentPercentage = intval($_GET['firstPayment']); //30
	$creditTime = intval($_GET['creditTime']); //36
	$casco = isset($_GET['casco']) && !empty($_GET['casco']) ? $_GET['casco'] : 0; //1



	/**
	 * @todo реализовать передачу аргуметов средствами DTO
	 */
	$creditCalculator = new CreditCalculator(
			$carPrice,
			$firstPaymentPercentage,
			$creditTime,
			$defaultValues->interestRate,
			$casco,
			$defaultValues->cascoPercentages
		);
	?>

	<div class="credit-values" id="credit-values">
		
		<table class="credit-table" id="credit-table">

			<tr>
				<td>
					Стоимость а/м, &#8381;
				</td>
				<td>
					Первоначальный взнос, &#8381; (%)
				</td>
				<td>
					Сумма кредита, &#8381;
				</td>
				<td>
					Срок кредита, мес
				</td>
				<td>
					Процентная ставка, %
				</td>
				<td>
					Ежемесячный платеж, &#8381;
				</td>
				<td>
					Стоимость КАСКО, &#8381;
				</td>
			</tr>

			<tr>
				<td>
					<?php echo $creditCalculator->getCarPrice(); ?>
				</td>
				<td>
					<?php echo $creditCalculator->getInitialPayment() . ' (' . $creditCalculator->getInitialPaymentPercentages() . '%)'; ?>
				</td>
				<td>
					<?php echo $creditCalculator->getAmountOfCredit(); ?>
				</td>
				<td>
					<?php echo $creditCalculator->getCreditTime(); ?>
				</td>
				<td>
					<?php echo $creditCalculator->getInterestRate(); ?>
				</td>
				<td>
					<?php echo $creditCalculator->getMonthlyPayment(); ?>
				</td>
				<td>
					<?php echo $creditCalculator->getCascoPrice(); ?>
				</td>
			</tr>
			
		</table>
	</div>

	<?php endif; ?>


		
	</body>
</html>