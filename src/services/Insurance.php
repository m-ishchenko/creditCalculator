<?php

namespace img\credit_calculator\services;

use img\credit_calculator\interfaces\AdditionalPreferencesInterface;
use img\credit_calculator\base\Base;
use img\credit_calculator\base\Booleans;

/**
 * Страхование жизни - указанный процент от стоимости а/м
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 * @final
 */
final class Insurance implements AdditionalPreferencesInterface
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
     * @param bool|int $needInsurance необходимость расчета страхования жизни
     * @param float $insurancePercentages процентная ставка для расчета страхования жизни
     */
	function __construct($needInsurance = 0, $insurancePercentages = null)
	{
        Base::validateServiceInput($needInsurance, $insurancePercentages);
		$this->needInsurance = Booleans::setBooleanValue($needInsurance);
		$this->insurancePercentages = $insurancePercentages;
	}

	/**
	 * Возвращает необходимость расчета страхования жизни
	 * 
	 * @access  public
	 * @return boolean необходимость учета страхования жизни
	 */
    public function isNeedable() {
		return $this->needInsurance;
	}

	/**
	 * Возвращает процентную ставку для расчета страхования жизни
	 * 
	 * @access  public
	 * @return float процентная ставка для расчета страхования жизни, %
	 */
    public function getPercentages() {
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
    public function setPrice($creditAmount) {
		return ($this->needInsurance) ? $creditAmount * $this->insurancePercentages / Base::PERCENTAGES_100 : 0;
	}
}