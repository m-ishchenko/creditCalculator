<?php
namespace creditCalc;

/**
 * Трейт включающий в себя методы валидации передаваемых классу кредитного калькулятора значений
 * Также содержит функцию округления аргумента и отображет исключения, в случае наличия ошибок валидации
 */
trait CreditCalculatorValidatorsTrait
{
	/**
	 * Количество символов округления
	 * 
	 * @access public
	 * @var integer
	 */
	public $roundCoefficient = 2;

	/**
	 * Проверка является ли аргумент числом
	 * 
	 * @access private
	 * @param  integer $arg аргумент, подлежащий проверке
	 * @return bool true|false      возвращает true, если переданный аргумент не пустой и является числом
	 * в остальных случаях - false
	 */
	private function validateNumber($arg) {
		if(empty(trim($arg))) {
			// return false;
			throw new \Exception("Переданно пустое значение");
			
		} else {
			if(is_float($arg) || is_int($arg)) {
				return true;
			} else {
				throw new \Exception("Переданное значение не является числом");
			}			
		}
	}

	/**
	 * Проверка является ли аргумент целым числом
	 * 
	 * @access private
	 * @param  integer $arg аргумент, подлежащий проверке
	 * @return bool true|false      возвращает true, если переданный аргумент не пустой и является целым числом
	 * в остальных случаях - false
	 */
	private function validateInteger($arg) {
		if(empty(trim($arg))) {
			// return false;
			throw new \Exception("Переданно пустое значение");
		} else {
			if(is_int($arg)) {
				return true;
			} else {
				throw new \Exception("Переданное значение не является целым числом");
			}			
		}
	}

	/**
	 * Возвращает округленное значение переданного аргумента
	 * 
	 * @access private
	 * @param float $value числовое значение аргумента
	 * @return float значение аргумента, округленное с точностью до значения аттрибута roundCoefficient
	 */
	private function setRoundedValue($value) {
		return round($value, $this->roundCoefficient);
	}
}