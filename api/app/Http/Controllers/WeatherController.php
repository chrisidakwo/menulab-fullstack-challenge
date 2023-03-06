<?php

namespace App\Http\Controllers;

use App\Services\WeatherService\Contracts\WeatherService;
use App\Services\WeatherService\NationalWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * @param Request $request
     * @param string $coordinate
     *
     * @return JsonResponse
     */
    public function index(Request $request, string $coordinate): JsonResponse
    {
        [$latitude, $longitude] = explode(',', trim(str_replace(' ', '', $coordinate)));

        $highlight = $request->boolean('highlight');

        if ($highlight) {
            $weatherData = $this->weatherService->getWeatherHighlight($latitude, $longitude);
        } else {
            $weatherData = $this->weatherService->getWeatherDetails($latitude, $longitude);
        }

        return response()->json([
            'data' => $weatherData,
        ]);
    }
}
