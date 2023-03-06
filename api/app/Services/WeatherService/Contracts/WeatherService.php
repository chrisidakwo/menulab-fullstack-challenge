<?php

namespace App\Services\WeatherService\Contracts;

interface WeatherService
{
    /**
     * Get summarized weather details.
     *
     * @param string $latitude
     * @param string $longitude
     *
     * @return array
     */
    public function getWeatherHighlight(string $latitude, string $longitude): array;

    /**
     * Get full weather details.
     *
     * @param string $latitude
     * @param string $longitude
     *
     * @return array
     */
    public function getWeatherDetails(string $latitude, string $longitude): array;
}
