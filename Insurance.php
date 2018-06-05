<?php

namespace creditCalc;

/**
 * Страхование жизни - указанный процент от стоимости а/м
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 * @final
 */
final class Insurance
{
	/**
	 * Необходимость расчета страхования жизни
	 * @access  private
	 * @var boolean true|false
	 */
	private $needInsurance;

	/**
	 * Процентная ставка для расчета страхования жизни
	 * @access  private
	 * @var float
	 */
	private $insurancePercentages;

	/**
	 * Присваивает переданные классу при инициализации аргументы приватным свойствам
	 * 
	 * @param boolean $needInsurance        необходимость расчета страхования жизни
	 * @param float  $insurancePercentages процентная ставка для расчета страхования жизни
	 */
	function __construct($needInsurance = 0, $insurancePercentages = null)
	{
		$this->needInsurance = $needInsurance;
		$this->insurancePercentages = $insurancePercentages;
	}

	/**
	 * Возвращает необходимость расчета страхования жизни
	 * 
	 * @access  public
	 * @return boolean необходимость учета страхования жизни
	 */
	public function isNeedInsurance() {
		return $this->needInsurance;
	}

	/**
	 * Возвращает процентную ставку для расчета страхования жизни
	 * 
	 * @access  public
	 * @return float процентная ставка для расчета страхования жизни, %
	 */
	public function getInsurancePercentages() {
		return $this->insurancePercentages;
	}

	/**
	 * Расчитывает сумму страхования жизни
	 * 
	 * ```
	 * СЖ = СК * ПССЖ / 100,
	 * где: 
	 * СЖ - страхование жизни
	 * ПССЖ - процентная ставка страхование жизни, %
	 * ```
	 * 
	 * @access  public
	 * @param float $creditAmount сумма кредита, руб
	 * @return float сумма страхования жизни, руб
	 */
	public function setInsurancePrice($creditAmount) {
		return $creditAmount * $this->insurancePercentages / SharedValues::PERCENTAGES_100;
	}
}