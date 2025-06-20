<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="weather-widget" style="border:2px solid #ccc; padding:10px; width: 400px; font-size:14px;">
    <h2>Прогноз погоды в городе <b><?= $arResult['CITY']; ?></b></h3>
    <p>🌡 Температура: <b><?= $arResult['TEMP']; ?></b></p>
    <p>💧 Влажность: <b><?= $arResult['HUMIDITY']; ?>%</b></p>
    <p>🌀 Давление: <b><?= $arResult['PRESSURE']; ?> мм рт. ст.</b></p>
</div>
