<?php

namespace App\Services\WeatherService;

use App\Services\WeatherService\Contracts\WeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NationalWeatherService implements WeatherService
{
    protected static string $baseUrl = 'https://api.weather.gov/points';

    /**
     * @inheritDoc
     */
    public function getWeatherHighlight(string $latitude, string $longitude): array
    {
        $url = $this->getWeatherApiUrl($latitude, $longitude);

        $response = Http::get($url);

        if ($response->ok()) {
            $properties = $response->json()['properties'];

            // We need this updated in case we have to work with datetime.
            config([
                'app.timezone' => $properties['timeZone']
            ]);

            $location = [
                'city' => $properties['relativeLocation']['properties']['city'],
                'state' => $properties['relativeLocation']['properties']['state'],
            ];

            return $this->getForecastSummary($properties['forecast'], [
                'location' => $location,
                'longitude' => $longitude,
                'latitude' => $latitude,
            ]);
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getWeatherDetails(string $latitude, string $longitude): array
    {
        $url = $this->getWeatherApiUrl($latitude, $longitude);

        $response = Http::get($url);

        if ($response->ok()) {
            $properties = $response->json()['properties'];

            // We need this updated in case we have to work with datetime.
            config([
                'app.timezone' => $properties['timeZone']
            ]);

            $location = [
                'city' => $properties['relativeLocation']['properties']['city'],
                'state' => $properties['relativeLocation']['properties']['state'],
            ];

            return $this->getForecastGridData($properties['forecastGridData']);
        }

        return [];
    }

    /**
     * @param string $url
     * @param array $data
     *
     * @return array
     */
    protected function getForecastSummary(string $url, array $data): array
    {
        $response = Http::get($url);

        if ($response->ok()) {
            $properties  = $response->json()['properties'];

            $currentWeatherData = (array) $properties['periods'][0];

            $currentWeatherData = [
                'isDayTime' => $currentWeatherData['isDaytime'],
                'temperature' => [
                    'value' => $currentWeatherData['temperature'],
                    'unit' => $currentWeatherData['temperatureUnit'],
                ],
                'temperatureTrend' => $currentWeatherData['temperatureTrend'],
                'precipitation' => [
                    'value' => $currentWeatherData['probabilityOfPrecipitation']['value'],
                    'unit' => $this->getMeasurementUnit($currentWeatherData['probabilityOfPrecipitation']['unitCode'])
                ],
                'humidity' => [
                    'value' => $currentWeatherData['relativeHumidity']['value'],
                    'unit' => $this->getMeasurementUnit($currentWeatherData['relativeHumidity']['unitCode']),
                ],
                'wind' => [
                    'speed' => $currentWeatherData['windSpeed'],
                    'direction' => $currentWeatherData['windDirection'],
                ],
                'icon' => str_replace('medium', 'large', $currentWeatherData['icon']),
                'shortDescription' => $currentWeatherData['shortForecast'],
            ];

            return array_merge($data, $currentWeatherData);
        }

        Log::emergency('FORECAST SUMMARY. Could not get response', [
            'status' => $response->status(),
            'json' => $response->json(),
        ]);

        return array_merge($data, []);
    }

    /**
     * @param string $url
     *
     * @return array
     */
    protected function getForecastGridData(string $url): array
    {
        $response = Http::get($url);

        if ($response->ok()) {
            $properties  = $response->json()['properties'];

            $temperature = $properties['temperature'];
            $precipitation = $properties['probabilityOfPrecipitation'];
            $humidity = $properties['relativeHumidity'];
            $wind = $properties['windSpeed'];
            $visibility = $properties['visibility'];
        }

        return [];
    }

    /**
     * Returns the human-understandable measurement unit from the provided unit code.
     *
     * @param string $unitCode
     *
     * @return string
     */
    protected function getMeasurementUnit(string $unitCode): string
    {
        return last(explode(':', $unitCode));
    }

    /**
     * @param string $latitude
     * @param string $longitude
     *
     * @return string
     */
    protected function getWeatherApiUrl(string $latitude, string $longitude): string
    {
        return sprintf("%s/%s,%s", self::$baseUrl, round($latitude, 4), round($longitude, 4));
    }
}
