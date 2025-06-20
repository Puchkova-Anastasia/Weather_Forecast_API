<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use weather\forecast\CurrentWeather;
use Bitrix\Main\Loader;
use Bitrix\Main\Data\Cache;

class WeatherWidget extends CBitrixComponent
{
    public function executeComponent()
    {
        if (Loader::includeModule('weather.forecast')) { // проверка подключения модуля

            // Получаем параметры компонента
            $apiKey = $this->arParams['API_KEY'];
            if (empty($apiKey)) {
                ShowError('API_KEY не задан');
                return;
            }

            $city = isset($this->arParams['CITY']) ? ($this->arParams['CITY']) : 'Moscow';
            $units = isset($this->arParams['UNITS']) ? ($this->arParams['UNITS']) : 'metric';

            // Создаем объект погоды и получаем данные
            try {
                $weatherObj = new CurrentWeather($apiKey, $city, $units);
                $weatherData = $weatherObj->getCurrentWeather();

                if (!$weatherData) {
                    ShowError('Не удалось получить данные о погоде');
                    return;
                }
                $temp = $weatherData['temp'] > 0 ? '+'. $weatherData['temp'] : (($weatherData['temp'] < 0) ? '-'. $weatherData['temp'] : 0); // проверяем знак температуры
                $tempUnit = ($units == 'metric') ? $temp . '°C' : (($units == 'imperial') ? $temp . '°F' : ''); // проверяем единицы измерения

                // Передаем данные в шаблон
                $this->arResult = [
                    'CITY' => $city,
                    'TEMP' => $tempUnit,
                    'HUMIDITY' => isset($weatherData['humidity']) ? $weatherData['humidity']: '',
                    'PRESSURE' => isset($weatherData['pressure']) ? $weatherData['pressure']: '',
                ];
            } catch (\Exception$e) {
                echo "Произошла ошибка: " . $e->getMessage(); //выводим сообщение об ошибке
            }

            // Кеширование вывода компонента
            if ($this->StartResultCache()) {
                parent::includeComponentTemplate();
                $this->EndResultCache();
            }

        } else {
            echo 'Модуль weather.forecast не установлен';
        }
    }
}
?>
