<?php

/**
 * Чтение файла конфигурации
 * @var object
 */
$config = include('config.inc.php');

if($config->debug) {
	error_reporting(0);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	error_reporting(E_ALL);
	ini_set("error_reporting", E_ALL);
	error_reporting(E_ALL & ~E_NOTICE);
}

function __autoload($class)
{
    $parts = explode('\\', $class);
    require end($parts) . '.php';
}