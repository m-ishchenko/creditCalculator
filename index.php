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
		<form id="credit-user-data" action="/getCreditData.php" method="post">

			<fieldset>
				<legend>Исходные данные</legend>

			<table>
				<tr>
					<td>						
						<label for="carPrice">Стоимость а/м, &#8381;</label>
					</td>
					<td>
						<input type="number" name="carPrice" id="carPrice" required min="<?php echo intval($config->minCreditPrice); ?>"><br>
					</td>
				</tr>
				<tr>
					<td>
						<label for="firstPayment">Первоначальный взнос, %</label>
					</td>
					<td>						
						<select name="firstPayment" id="firstPayment" required>
							<?php foreach ($config->firstPaymentArray as $key => $firstPaymentValue): ?>
								<option value="<?php echo $firstPaymentValue; ?>" ><?php echo $firstPaymentValue; ?>%</option>
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
								<option value="<?php echo $creditTimeValue; ?>"> <?php echo $creditTimeValue; ?> мес.</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="deferred">Отложенный платеж</label>
					</td>
					<td>
						<input type="checkbox" name="deferred" class="deferred" id="deferred" onclick="toggleByCheckbox('deferred', 'deferredPayment');">

						<select name="deferredPayment" id="deferredPayment" style="visibility: hidden" required>
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
						<input type="checkbox" name="casco" class="casco" id="casco">
					</td>
				</tr>
				<tr>
					<td>
						<label for="insurance">Страхование жизни</label>
					</td>
					<td>
						<input type="checkbox" name="insurance" class="insurance" id="insurance">
					</td>
				</tr>

			</table>

			<input type="submit" value="Рассчитать" class="submit" id="submit">
			<a href="javascript:void(0)" onclick="resetForm()">Сбросить</a>

			</fieldset>
		</form>
	</div>

	<div id="result" class="credit-values" style="display: none">

		<fieldset>
			<legend>Результаты расчета</legend>

			<table class="credit-table" id="printableArea">
				<thead>
					<th>
						<td colspan="8">
							<h2>Стоимость а/м <span id="carPriceValue"></span> &#8381;</h2>
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
							<span id="creditAmountValue"></span> &#8381;
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="cascoPercentagesValue"></span>  %
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="insurancePercentagesValue"></span> %
						</td>
						<td>
							Процентная ставка
						</td>
						<td>
							<span id="deferredPercentagesValue"></span> %						
						</td>
					</tr>
					<tr>
						<td>2</td>
						<td>
							Срок кредита
						</td>
						<td>
							<span id="creditTimeValue"></span> мес
						</td>
						<td>
							Стоимость КАСКО
						</td>
						<td>
							<span id="cascoPriceValue"></span> &#8381;
						</td>
						<td>
							Стоимость СЖ
						</td>
						<td>
							<span id="insurancePriceValue"></span> &#8381;
						</td>
						<td>
							Размер отложенного платежа
						</td>
						<td>
							<span id="deferredPaymentValue"></span> &#8381;
						</td>
					</tr>
					<tr>
						<td>3</td>
						<td>
							Первоначальный взнос
						</td>
						<td>
							<span id="initialPaymentValue"></span> &#8381; (<span id="initialPaymentPercentValue"></span>  %)
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
							<span id="interestRateValue"></span> %
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
							<span id="montlyPaymentValue"></span>  &#8381;
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

	</div>
		
	</body>
</html>


<script type="text/javascript" src="main.js">
	
</script>
<script type="text/javascript">
	var formName = document.getElementById('credit-user-data');
	proceedAjaxCalculate(formName);
</script>