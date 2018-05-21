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
 * {		window.print();	} печать страницы
 */
document.querySelector("#print").addEventListener("click", function() {
	window.print();
});


// function proceedAjaxCalculate(e) {
	// console.log('proceedAjaxCalculate');

// }	
// var creditInputForm = document.getElementById('credit-user-data');
// if(creditInputForm.addEventListener){
    // creditInputForm.addEventListener("submit", proceedAjaxCalculate, false);  //Modern browsers
// }else if(creditInputForm.attachEvent){
    // creditInputForm.attachEvent('onsubmit', proceedAjaxCalculate);            //Old IE
// }
