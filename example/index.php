<?php
$config = include('config.inc.php');

use CreditCalculator\CreditData;
use CreditCalculator\Base;
use CreditCalculator\Casco;
use CreditCalculator\Insurance;
use CreditCalculator\Deferred;
use CreditCalculator\AnnuityCalculator;
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

			<fieldset>
				<legend>Исходные данные</legend>

			<table>
				<tr>
					<td>						
						<label for="carPrice">Стоимость а/м, &#8381;</label>
					</td>
					<td>
						<input type="number" name="carPrice" id="carPrice" value="<?php echo $_GET['carPrice']; ?>" required min="<?php echo intval($config->minCreditPrice); ?>"><br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="firstPayment">Первоначальный взнос, %</label>
					</td>
					<td>						
						<select name="firstPayment" id="firstPayment" required>
							<?php foreach ($config->firstPaymentArray as $key => $firstPaymentValue): ?>
								<?php $firstPaymentSelected = ($firstPaymentValue == $_GET['firstPayment']) ? 'selected' : null; ?>
								<option value="<?php echo $firstPaymentValue; ?>" <?php echo $firstPaymentSelected; ?> ><?php echo $firstPaymentValue; ?>%</option>
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
								<?php $creditTimeSelected = ($creditTimeValue == $_GET['creditTime']) ? 'selected' : null; ?>
								<option value="<?php echo $creditTimeValue; ?>" <?php echo $creditTimeSelected; ?> > <?php echo $creditTimeValue; ?> мес.</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="deferred">Отложенный платеж</label>
					</td>
					<td>
						<?php $isDeferredChecked = (isset($_GET['deferred'])) ? 'checked' : null; ?>
						<?php $isDeferredPayentVisible = (isset($_GET['deferred'])) ? 'unset' : 'hidden'; ?>
						<input type="checkbox" name="deferred" class="deferred" id="deferred" <?php echo $isDeferredChecked; ?> onclick="toggleByCheckbox('deferred', 'deferredPayment');">

						<select name="deferredPayment" id="deferredPayment" style="visibility: <?php echo $isDeferredPayentVisible; ?>" required>
							<?php foreach ($config->deferredPaymentArray as $key => $deferredPaymentValue) : ?>
								<option value="<?php echo $deferredPaymentValue; ?>"> <?php echo $deferredPaymentValue; ?> %</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr>
					<td>
						<label for="casco">КАСКО</label>
					</td>
					<td>						
						<?php $isCascoChecked = (isset($_GET['casco'])) ? 'checked' : null; ?>
						<input type="checkbox" name="casco" class="casco" id="casco" <?php echo $isCascoChecked; ?> >
					</td>
				</tr>
				<tr>
					<td>
						<label for="insurance">Страхование жизни</label>
					</td>
					<td>
						<?php $isInsuranceChecked = (isset($_GET['insurance'])) ? 'checked' : null; ?>
						<input type="checkbox" name="insurance" class="insurance" id="insurance" <?php echo $isInsuranceChecked; ?> >
					</td>
				</tr>

			</table>

			<input type="submit" value="Рассчитать" class="submit" id="submit">
			<a href="/" onclick="resetForm()">Сбросить</a>

			</fieldset>
		</form>
	</div>

	<?php if( isset($_GET['carPrice']) && isset($_GET['firstPayment']) && isset($_GET['creditTime'])  && isset($_GET['deferredPayment'])): ?>

	<?php

		$needCasco = isset($_GET['casco']) ? $_GET['casco'] : null;
		$needInsurance = isset($_GET['insurance']) ? $_GET['insurance'] : null;
		$needDeferred = isset($_GET['deferred']) ? $_GET['deferred'] : null;

		$credit = new CreditData($_GET['carPrice'], $_GET['firstPayment'], $_GET['creditTime'], $config->interestRate);
		$casco = new Casco($needCasco, $config->cascoPercentages);
		$insurance = new Insurance($needInsurance, $config->insurancePercentages);
		$deferred = new Deferred($needDeferred, $_GET['deferredPayment']);

		$creditCalculator = new AnnuityCalculator($credit, $casco, $insurance, $deferred);

	?>
	<div id="result" class="credit-values">

		<fieldset>
			<legend>Результаты расчета</legend>

			<table class="credit-table" id="printableArea">
				<thead>
					<th>
						<td colspan="8">
							<h2>Стоимость а/м <span id="carPriceValue"><?php echo $creditCalculator->getCarPrice(); ?></span> &#8381;</h2>
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
							<span id="creditAmountValue"><?php echo $creditCalculator->getAmountOfCredit(); ?></span> &#8381;
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="cascoPercentagesValue"><?php echo $creditCalculator->getCascoPercentages(); ?></span>  %
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="insurancePercentagesValue"><?php echo $creditCalculator->getInsurancePercentages(); ?></span> %
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="deferredPercentagesValue"><?php echo $creditCalculator->getDeferredPercentages(); ?></span> %						
						</td>
					</tr>
					<tr>
						<td>2</td>
						<td>
							Срок кредита
						</td>
						<td>
							<span id="creditTimeValue"><?php echo $creditCalculator->getCreditTime(); ?></span> мес
						</td>
						<td>
							Стоимость КАСКО
						</td>
						<td>
							<span id="cascoPriceValue"><?php echo $creditCalculator->getCascoPrice(); ?></span> &#8381;
						</td>
						<td>
							Стоимость СЖ
						</td>
						<td>
							<span id="insurancePriceValue"><?php echo $creditCalculator->getInsurancePrice(); ?></span> &#8381;
						</td>
						<td>
							Размер отложенного платежа
						</td>
						<td>
							<span id="deferredPaymentValue"><?php echo $creditCalculator->getDeferredPaymentPrice(); ?></span> &#8381;
						</td>
					</tr>
					<tr>
						<td>3</td>
						<td>
							Первоначальный взнос
						</td>
						<td>
							<span id="initialPaymentValue"><?php echo $creditCalculator->getInitialPayment(); ?></span> &#8381; (<span id="initialPaymentPercentValue"><?php echo $creditCalculator->getInitialPaymentPercentages(); ?></span>  %)
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
							<span id="interestRateValue"><?php echo $creditCalculator->getInterestRate(); ?></span> %
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
							<span id="montlyPaymentValue"><?php echo $creditCalculator->getMonthlyPayment(); ?></span>  &#8381;
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
		
		</fieldset>

		<?php endif; ?>

	</div>
		
	</body>
</html>


<script type="text/javascript" src="main.js"></script>
<!-- <script type="text/javascript">
	var formName = document.getElementById('credit-user-data');
	proceedAjaxCalculate(formName);
</script> -->