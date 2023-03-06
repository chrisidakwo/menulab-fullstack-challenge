<?php

namespace App\Services;

use App\Models\User;
use App\Services\WeatherService\Contracts\WeatherService;
use Illuminate\Contracts\Cache\Repository;
use Psr\SimpleCache\InvalidArgumentException;

class UserService
{
    protected WeatherService $weatherService;
    protected Repository $cacheStore;

    public function __construct(WeatherService $weatherService, Repository $cacheStore)
    {
        $this->weatherService = $weatherService;
        $this->cacheStore = $cacheStore;
    }

    /**
     * @param bool $highlight
     * @param string $latitude
     * @param string $longitude
     * @param User $user
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getWeatherData(bool $highlight, string $latitude, string $longitude, User $user): array
    {
        $cacheKey = $this->getCacheKey($highlight, $latitude, $longitude, $user->getKey());

        $data = $this->cacheStore->get($cacheKey);

        if (! $data) {
            $data = match ($highlight) {
                true => $this->weatherService->getWeatherHighlight($latitude, $longitude),
                false => $this->weatherService->getWeatherDetails($latitude, $longitude),
            };

            // Cache data only if it contains the full response as expected
            if (array_key_exists('shortDescription', $data)) {
                $this->cacheStore->put($cacheKey, $data, now()->addHour()->getTimestamp());
            }
        }

        return $data;
    }

    private function getCacheKey($latitude, $longitude, $id): string
    {
        return sprintf("%s_%s_%s", $id, $longitude, $latitude);
    }
}
