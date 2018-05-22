/**
 * скрывает/показывает секцию по нажатию checkbox
 * @param  string checkboxID id элемента checkbox
 * @param  string blockID    id элемента div
 */
function toggleByCheckbox(checkboxID, blockID) {
	var checkBox = document.getElementById(checkboxID);
	var block = document.getElementById(blockID);

	if (checkBox.checked == true){
	  block.style.visibility = "unset";
	} else {
	  block.style.visibility = "hidden";
	}
}

/**
 * Функция печати расчета
 * { window.print(); } печать страницы
 */
document.querySelector("#print").addEventListener("click", function() {
	window.print();
});

/**
 * Сброс значений формы ввода первоначальных данных
 */
function resetForm() {
	document.getElementById('credit-user-data').reset();
	document.getElementById('deferredPayment').style.visibility = "hidden";
}

/**
 * Установка елементу страницы значения из ответа ajax-запроса
 * @param string elementID ID элемента на странице
 * @param string JSONvalue ключ ответа ajax-запроса
 */
function setFromJSON(elementID, JSONvalue) {
	document.getElementById(elementID).innerHTML = JSONvalue; 
}

/**
 * ajax-отправка значений формы обработчику
 * @param  string form ID формы, содержащей первоначальные сведения для расчета кредита
 * @return {[type]}      [description]
 */
function proceedAjaxCalculate(form) {
	form.onsubmit = function (e) {
		e.preventDefault();
		var data = {};
		for (var i = 0, ii = form.length; i < ii; ++i) {
			var input = form[i];
			if (input.name) {
				if(input.type == 'checkbox') {
					data[input.name] = input.checked ? "1" : "0";
				} else {
				 	data[input.name] = input.value;
				}
			}
		}

		var xhr = new XMLHttpRequest();
		xhr.open(form.method, form.action, true);
		xhr.setRequestHeader('Accept', 'application/json; charset=utf-8');
		xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

		xhr.onreadystatechange = function() {
		    if(xhr.status == 200) {
		    	var creditData = JSON.parse(xhr.responseText);

		        console.log(xhr.responseText);
		        setFromJSON('carPriceValue', creditData['carPrice']);
		        setFromJSON('creditAmountValue', creditData['creditAmount']);
		        setFromJSON('cascoPercentagesValue', creditData['cascoPercent']);
		        setFromJSON('insurancePercentagesValue', creditData['insurancePercent']);
		        setFromJSON('deferredPercentagesValue', creditData['deferredPercent']);
		        setFromJSON('creditTimeValue', creditData['creditTime']);
		        setFromJSON('cascoPriceValue', creditData['cascoPrice']);
		        setFromJSON('insurancePriceValue', creditData['insurancePrice']);
		        setFromJSON('deferredPaymentValue', creditData['deferredPayment']);
		        setFromJSON('initialPaymentValue', creditData['initPayment']);
		        setFromJSON('initialPaymentPercentValue', creditData['initPaymentPercent']);
		        setFromJSON('interestRateValue', creditData['interestRate']);
		        setFromJSON('montlyPaymentValue', creditData['monthPayment']);
		        document.getElementById('result').style.display = 'block';
		    } else if(xhr.status == 400) {
		    	console.log("Ошибка 400. Bad Request.");
		    } else {
		    	console.log("Неопознанная ошибка");
		    }
		}
		xhr.send(JSON.stringify(data));
	}
}