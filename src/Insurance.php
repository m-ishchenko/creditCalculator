<?php
namespace img\credit_calculator;

/**
 * Страхование жизни - указанный процент от стоимости а/м
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://www.gnu.org/licenses/gpl-3.0.ru.html
 * @version 1.1
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
     * @param bool|int $needInsurance необходимость расчета страхования жизни
     * @param float $insurancePercentages процентная ставка для расчета страхования жизни
     */
	function __construct($needInsurance = 0, $insurancePercentages = null)
	{
        if(Base::checkIsNull($needInsurance) && Base::checkIsNull($insurancePercentages)){
            if(!Base::validateNumbers($insurancePercentages, Base::FLOAT_VALIDATOR)) {
                throw new \InvalidArgumentException('Процентная ставка страхования жизни должна быть числом');
            }
		} else {
		    throw new \InvalidArgumentException('Страхование жизни. Переданы пустые значения');
        }

		$this->needInsurance = Booleans::setBooleanValue($needInsurance);
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
		return ($this->needInsurance) ? $creditAmount * $this->insurancePercentages / Base::PERCENTAGES_100 : 0;
	}
}