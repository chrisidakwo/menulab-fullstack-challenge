<?php

namespace App\Services\WeatherService\Contracts;

interface WeatherService
{
    /**
     * @param bool $highlight
     * @param string $latitude
     * @param string $longitude
     *
     * @return array
     */
    public function getWeatherData(bool $highlight, string $latitude, string $longitude): array;
}
