<?php

namespace App\Services\WeatherService;

use App\Services\WeatherService\Contracts\WeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NationalWeatherService implements WeatherService
{
    protected static string $baseUrl = 'https://api.weather.gov/points';

    /**
     * @param bool $highlight
     * @param string $latitude
     * @param string $longitude
     *
     * @return array
     */
    protected function getWeatherData(bool $highlight, string $latitude, string $longitude): array
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

            return match ($highlight) {
                true  => $this->getForecastSummary($properties['forecast'], [
                    'location' => $location,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                ]),

                false => $this->getForecastGridData($properties['forecast'], [
                    'location' => $location,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                ]),
            };
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getWeatherHighlight(string $latitude, string $longitude): array
    {
        return $this->getWeatherData(true, $latitude, $longitude);
    }

    /**
     * @inheritDoc
     */
    public function getWeatherDetails(string $latitude, string $longitude): array
    {
        return $this->getWeatherData(false, $latitude, $longitude);
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
     * @param array $data
     *
     * @return array
     */
    protected function getForecastGridData(string $url, array $data): array
    {
        $response = Http::get($url);

        $series = [];

        if ($response->ok()) {
            $properties  = $response->json()['properties'];

            $periods = $properties['periods'];

            foreach ($periods as $period) {
                $series[$period['name']] = [
                    'isDayTime' => $period['isDaytime'],
                    'temperature' => [
                        'value' => $period['temperature'],
                        'unit' => $period['temperatureUnit'],
                    ],
                    'temperatureTrend' => $period['temperatureTrend'],
                    'precipitation' => [
                        'value' => $period['probabilityOfPrecipitation']['value'],
                        'unit' => $this->getMeasurementUnit($period['probabilityOfPrecipitation']['unitCode'])
                    ],
                    'humidity' => [
                        'value' => $period['relativeHumidity']['value'],
                        'unit' => $this->getMeasurementUnit($period['relativeHumidity']['unitCode']),
                    ],
                    'wind' => [
                        'speed' => $period['windSpeed'],
                        'direction' => $period['windDirection'],
                    ],
                    'icon' => str_replace('medium', 'large', $period['icon']),
                    'shortDescription' => $period['shortForecast'],
                ];
            }
        }

        return array_merge($data, [
            'series' => $series,
        ]);
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
