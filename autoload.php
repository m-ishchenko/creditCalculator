<?php

/**
 * Автозагрузка классов
 * 
 * установка уровня отображения ошибок
 * считывает файл конфигурации
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license GPLv3 https://www.gnu.org/licenses/gpl-3.0.ru.html
 */
$config = include('config.inc.php');

if($config->debug) {
	error_reporting(0);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(E_ALL);
	ini_set("error_reporting", E_ALL);
	error_reporting(E_ALL & ~E_NOTICE);
}

/**
 * Автозагрузка классов
 * 
 * @param  string $class имя класса
 * @return string        имя файла класса
 */
function __autoload($class)
{
    $parts = explode('\\', $class);
    require end($parts) . '.php';
}