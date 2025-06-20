<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

// Обработчик установки модуля
class weather_forecast extends CModule
{
    public $MODULE_ID = 'weather.forecast';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__.'/version.php');

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = 'Weather Forecast';
        $this->MODULE_DESCRIPTION = 'Модуль для получения текущей погоды';
        $this->PARTNER_NAME = 'OpenWeatherMap API';
        $this->PARTNER_URI = 'https://openweathermap.org/api';
    }

    public function DoInstall()
    {
        $this->InstallDB();
    }

    public function InstallDB($arParams = array())
    {
      RegisterModule('weather.forecast');
      return true;
    }

    public function InstallFiles($arParams = array())
    {
        return true;
    }

    public function UnInstallFiles()
    {
      return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
    }

    public function UnInstallDB($arParams = array())
    {
       UnRegisterModule('weather.forecast');
       return true;
    }

}
