<?php

namespace Tests\Unit\Services\WeatherService;

use App\Services\WeatherService\NationalWeatherService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class NationalWeatherServiceTest extends TestCase
{
    public function test_it_calls_the_right_forecast_method()
    {
        $mock = $this->mock(NationalWeatherService::class, function (MockInterface $m) {
            $m->shouldReceive('getWeatherHighlight')
                ->once()
                ->andReturn($this->getFakeWeatherHighlightResponse());
        });

        $this->app->bind(NationalWeatherService::class, fn () => $mock);

        resolve(NationalWeatherService::class)->getWeatherHighlight(
            '35.1976',
            '-91.9449'
        );
    }

    public function test_it_gets_weather_highlight_data(): void
    {
        Http::fake([
            'https://api.weather.gov/points/*' => Http::response($this->getApiResponseJsonData())
        ]);

        Http::fake([
            'https://api.weather.gov/gridpoints/*' => Http::response($this->getForecastJsonData())
        ]);

        /** @var NationalWeatherService $service */
        $service = $this->app->make(NationalWeatherService::class);

        $weatherData = $service->getWeatherData(
            true,
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

    public function test_it_gets_weather_details_data(): void
    {
        Http::fake([
            'https://api.weather.gov/gridpoints/*' => Http::response($this->getForecastJsonData())
        ]);

        /** @var NationalWeatherService $service */
        $service = $this->app->make(NationalWeatherService::class);

        $weatherData = $service->getWeatherData(
            false,
            '35.1976',
            '-91.9449'
        );

        self::assertContains('series', array_keys($weatherData));

        $series = $weatherData['series'];

        $this->assertCount(14, $series);

        $this->assertEquals('Today', $series[0]['name']);
        $this->assertEquals('Tonight', $series[1]['name']);
    }

    /**
     * @return array
     */
    private function getForecastJsonData(): array
    {
        return (array) json_decode(File::get(app_path('../tests/data/forecast.json')));
    }

    /**
     * @return array
     */
    private function getApiResponseJsonData(): array
    {
        return (array) json_decode(File::get(app_path('../tests/data/response.json')));
    }

    private function getFakeWeatherHighlightResponse(): array
    {
        return [
            'latitude' => '30.9627',
            'longitude' => '-91.0017',
            'isDayTime' => true,
            'temperature' => [
                'value' => 75,
                'unit' => 'F',
            ],
            'temperatureTrend' => 'falling',
            'precipitation' => [
                'value' => null,
                'unit' => 'percent'
            ],
            'humidity' => [
                'value' => 87,
                'unit' => 'percent',
            ],
            'wind' => [
                'speed' => '10 to 15 mph',
                'direction' => 'SW',
            ],
            'icon' => 'https://api.weather.gov/icons/land/day/bkn?size=large',
            'shortDescription' => 'Partly Sunny',
        ];
    }
}
