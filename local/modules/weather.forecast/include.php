<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses('weather.forecast', [
    'weather\\forecast\\CurrentWeather' => 'lib/CurrentWeather.php',
]);
