<?php

namespace img\credit_calculator\services;

use img\credit_calculator\interfaces\AdditionalPreferencesInterface;
use img\credit_calculator\interfaces\CalculateDeferredInterface;
use img\credit_calculator\base\Base;
use img\credit_calculator\base\Booleans;

/**
 * Вспомогательный класс, предназначенный для передачи предварительно рассчитанных значений расчета отложенного платежа кредитному калькулятору
 * 
 * Отложенный платеж может составлять 20-40% от итоговой стоимости а/м
 * Вносится с последним платежом по кредиту
 * На сумму отложенного платежа начисляются проценты и уплачиваются вместе с основным долгом
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 2.0
 * @final
 */
final class Deferred implements CalculateDeferredInterface, AdditionalPreferencesInterface
{
	/**
	 * Необходимость расчета отложенного платежа
	 * 
	 * @access  private
	 * @var boolean true|false
	 */
	private $needDeferred;

	/**
	 * Процентная ставка для расчета отложенного платежа
	 * 
	 * @access  private
	 * @var float
	 */
	private $deferredPercentages;

    /**
     * Присваивает переданные классу при инициализации аргументы приватным свойствам
     *
     * @param bool|int $needDeferred необходимость расчета отложенного платежа
     * @param float $deferredPercentages процентная ставка для расчета отложенного платежа
     */
	function __construct($needDeferred = 0, $deferredPercentages = null)
	{
        Base::validateServiceInput($needDeferred, $deferredPercentages);
		$this->needDeferred = Booleans::setBooleanValue($needDeferred);
		$this->deferredPercentages = $deferredPercentages;
	}

	/**
	 * Необходимость расчета отложенного платежа
	 * 
	 * @access  public
	 * @return boolean необходимость учета отложенного платежа
	 */
    public function isNeedable() {
		return ($this->needDeferred) ? $this->needDeferred : 0;
	}

	/**
	 * Размер отложенного платежа, % 
	 * 
	 * @access  public
	 * @return float размер отложенного платежа, %
	 */
    public function getPercentages() {
		return $this->deferredPercentages;
	}

	/**
	 * Стоимость процентной ставки отложенного платежа, руб
	 * 
	 * ```
	 * ПСОП = СОП * ПСК / (100 * 12),
	 * где:
	 * ПСОП - процентная ставка отложенного платежа
	 * СОП - сумма отложенного платежа
	 * ПСК - процентная ставка по кредиту
	 * ```
	 * 
	 * @access  public
	 * @param float $carPrice     итоговая стоимость а/м
	 * @param float $interestRate процентная ставка по кредиту
	 * @return  float
	 */
	public function setDeferredPercentagesPrice($carPrice, $interestRate) {
		return ($this->needDeferred) ? $this->setPrice($carPrice) * $interestRate / (Base::PERCENTAGES_100 * Base::MONTHS_IN_YEAR) : 0;
	}

	/**
	 * Устанавливает значение стоимости отложенного платежа
	 * 
	 * ```
	 * ОП = САМ * РОП / 100,
	 * где:
	 * ОП - отложенный платеж
	 * САМ - стоимость а/м, руб
	 * РОП - размер отложенного платежа, руб
	 * ```
	 * 
	 * @access  public
	 * @param float $carPrice итоговая стоимость а/м
	 * @return  float стоимость отложенного платежа, руб
	 */
    public function setPrice($carPrice) {
		return ($this->needDeferred) ? $carPrice * $this->deferredPercentages / Base::PERCENTAGES_100 : 0;
	}
}