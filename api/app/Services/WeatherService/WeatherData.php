<?php

namespace App\Services\WeatherService;

class WeatherData
{
    public string $timezone = '';
    public bool|null $isDayTime = null;
    public int|float $temperature;
    public string $temperatureUnit;
    public string $precipitation;
    public string $humidity;
    public string $wind;
    public string $windDirection;
    public string $icon;
    public string $shortForecast;
}
