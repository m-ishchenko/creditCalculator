<?php
namespace img\credit_calculator;

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
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 * @final
 */
final class Deferred
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
	 * @param bool $needDeferred        необходимость расчета отложенного платежа
	 * @param float $deferredPercentages процентная ставка для расчета отложенного платежа
	 */
	function __construct($needDeferred, $deferredPercentages = null)
	{
		try {
//			if(Base::checkIsNull($needDeferred)){
//				Base::validateNumbers($needDeferred, Base::BOOLEAN_VALIDATOR);
//			}
			if(Base::checkIsNull($deferredPercentages)){
				Base::validateNumbers($deferredPercentages, Base::FLOAT_VALIDATOR);
			}
		} catch (Exception $e) {
			print('Ошибка валидации: ' .$e->getMessage());
		}
		$this->needDeferred = $needDeferred;

		$this->deferredPercentages = $deferredPercentages;
	}

	/**
	 * Необходимость расчета отложенного платежа
	 * 
	 * @access  public
	 * @return boolean необходимость учета отложенного платежа
	 */
	public function isNeedDeferred() {
		return $this->needDeferred;
	}

	/**
	 * Размер отложенного платежа, % 
	 * 
	 * @access  public
	 * @return float размер отложенного платежа, %
	 */
	public function getDeferredPercentages() {
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
		return $this->setDeferredPrice($carPrice) * $interestRate / (Base::PERCENTAGES_100 * Base::MONTHS_IN_YEAR);
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
	public function setDeferredPrice($carPrice) {
		return $carPrice * $this->deferredPercentages / Base::PERCENTAGES_100;
	}
}