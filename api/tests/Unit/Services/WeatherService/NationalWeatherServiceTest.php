<?php

namespace Tests\Unit\Services\WeatherService;

use App\Services\WeatherService\NationalWeatherService;
use Tests\TestCase;

class NationalWeatherServiceTest extends TestCase
{
    public function test_it_gets_weather_highlight_data(): void
    {
        /** @var NationalWeatherService $service */
        $service = $this->app->make(NationalWeatherService::class);

        $weatherData = $service->getWeatherHighlight(
            '35.1976',
            '-91.9449'
        );

        $this->assertIsArray($weatherData);

        $this->assertArrayHasKey('precipitation', $weatherData);
        $this->assertIsArray($weatherData['precipitation']);

        $this->assertArrayHasKey('temperature', $weatherData);

        $this->assertArrayHasKey('humidity', $weatherData);
        $this->assertIsArray($weatherData['humidity']);
    }
}
