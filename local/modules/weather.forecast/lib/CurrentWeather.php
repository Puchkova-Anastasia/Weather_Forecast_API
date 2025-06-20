<?php
namespace weather\forecast;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Web\Json;

#https://openweathermap.org/current - API DOC

class CurrentWeather
{
    protected $apiKey;
    protected $city;
    protected $units;

    public function __construct($apiKey, $city = 'Moscow', $units = 'metric')
    {
        $this->apiKey = $apiKey; //ключ OpenWeatherMap Api
        $this->city = $city;
        $this->units = $units;
    }

    //получить данные с openweathermap через Curl
    private function proccessRequest($apiUrl)
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_URL, $apiUrl);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($request, CURLOPT_VERBOSE, 0);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($request);

        curl_close($request);

        $data = json_decode($response, true); //получение в виде массива

        return $data;
    }

    /**
     * Возвращает данные о погоде на текущий день (температура, влажность, давление) или null при ошибке.
     * @return array|null
     */
    public function getCurrentWeather()
    {
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $this->city ."&lang=ru&units=". $this->units . "&APPID=" . $this->apiKey; //необходимый url для запроса
        $result = []; //объявляем массив, в котором будут храниться нужные параметры

        // кеширование
        $cache = Cache::createInstance();
        $cacheTime = 1800; //30 минут
        //если кеш есть
        if ($cache->initCache($cacheTime, "cache_key_{$apiUrl}")) { //кэш проверяется при одном и том же урле апи
            // получаем кеш в переменную
            $vars = $cache->getVars();
            // выводим кэш
            return $vars;
        } elseif ($cache->startDataCache()) { // если кеша нет

            $data = $this->proccessRequest($apiUrl); //получение данных

            if (isset($data['main'])) {
                // При получении данных выводим температуру, влажность, давление
                $result = [
                    'temp' => isset($data['main']['temp']) ? intval($data['main']['temp']) : null,
                    'humidity' => isset($data['main']['humidity']) ? $data['main']['humidity'] : null,
                    'pressure' => isset($data['main']['pressure']) ? $data['main']['pressure'] : null,
                ];

                // записываем полученные данные в кеш
                $cache->endDataCache($result);
                return $result;
            }else {
                return null;
            }
        }
    }
}
