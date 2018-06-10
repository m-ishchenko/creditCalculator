Калькулятор расчета автокредита. Расчет аннуитета.
================================

Установка
------

Для добавления пакета в проект необходимо запустить

~~~
composer require maximishchenko/credit_calculator "dev-master"
~~~

Либо добавить 

~~~
"maximishchenko/credit_calculator": "dev-master"
~~~

в секцию ```require``` файла ```composer.json```

Использование
------

Условия кредита:

Объявить класс ```maximishchenko\credit_calculator\CreditData```.

Передать значения: 

* стоимость а/м (руб., в примере - 5 100 000)
* размер первоначального платежа (%, в примере - 30)
* срок кредита (мес., в примере - 24)
* процентная ставка (%, в примере - 10.9)

Расчет КАСКО:

Объявить класс ```maximishchenko\credit_calculator\Casco```.

Передать значения: 

* необходимость учета КАСКО (boolean, в примере - 1, может принимать значения: true|false, on|off, 1|0)
* процентная ставка КАСКО (%, в примере - 0.71%)

Расчет страхования жизни:

Объявить класс ```maximishchenko\credit_calculator\Insurance```.

Передать значения: 

* необходимость учета страхования жизни (boolean, в примере - 1, может принимать значения: true|false, on|off, 1|0) 
* процентная ставка страхования жизни (%, в примере - 17%)

Расчет отложенного платежа:

Объявить класс ```maximishchenko\credit_calculator\Deferred```.

Передать значения: 

* необходимость учета отложенного платежа (boolean, в примере - 1, может принимать значения: true|false, on|off, 1|0)
* процентная ставка отложенного платежа (%, в примере - 20%)

~~~
use maximishchenko\credit_calculator\CreditData;
use maximishchenko\credit_calculator\Base;
use maximishchenko\credit_calculator\Casco;
use maximishchenko\credit_calculator\Insurance;
use maximishchenko\credit_calculator\Deferred;
use maximishchenko\credit_calculator\AnnuityCalculator;

$credit = new CreditData(5100000, 30, 24, 10.9);
$casco = new Casco(1, 0.71);
$insurance = new Insurance(1, 17);
$deferred = new Deferred(1, 20);

$creditCalculator = new AnnuityCalculator($credit, $casco, $insurance, $deferred);
~~~

После объявления класса ```maximishchenko\credit_calculator\AnnuityCalculator``` становятся доступны следующие методы

* Данные кредита:

    * ```$creditCalculator->getCarPrice()``` - возвращает стоимость а/м, руб
    * ```$creditCalculator->getInitialPayment()``` - возвращает размер первоначального взноса, руб
    * ```$creditCalculator->getAmountOfCredit()``` - возвращает размер суммы кредита, руб
    * ```$creditCalculator->getInitialPaymentPercentages()``` - возвращает размер суммы кредита, %
    * ```$creditCalculator->getCreditTime()``` - возвращает срок кредита, мес
    * ```$creditCalculator->getInterestRate()``` - возвращает размер процентной ставки, %
    * ```$creditCalculator->getMonthlyPayment()``` - возвращает размер ежемесячного платежа, руб
    
* КАСКО

    * ```$creditCalculator->getCascoPrice()``` - возвращает размер стоимости КАСКО, руб
    * ```$creditCalculator->getCascoPercentages()``` - возвращает размер процентной ставки КАСКО, %

* Страхование жизни
    
    * ```$creditCalculator->getInsurancePrice()``` - возвращает размер стоимости страхования жизни, руб
    * ```$creditCalculator->getInsurancePercentages()``` - возвращает размер процентной ставки страхования жизни, руб
    
* Отложенный платеж

    * ```$creditCalculator->getDeferredPaymentPrice()``` - возвращает размер стоимости отложенного платежа, руб
    * ```$creditCalculator->getDeferredPercentages()``` - возвращает размер процентной ставки отложенного платежа, %

Пример использования находится в каталоге ```example```

Значения по-умолчанию находятся в файле ```example/config.inc.php```



