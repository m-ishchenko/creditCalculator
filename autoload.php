<?php

function __autoload($class)
{
    $parts = explode('\\', $class);
    require end($parts) . '.php';
}