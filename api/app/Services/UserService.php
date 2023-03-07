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
     * @param User $user
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getWeatherData(bool $highlight, User $user): array
    {
        $cacheKey = $this->getCacheKey($highlight, $user->latitude, $user->longitude, $user->getKey());

        $data = $this->cacheStore->get($cacheKey);

        if (! $data) {
            $data = $this->weatherService->getWeatherData($highlight, $user->latitude, $user->longitude);

            // Cache data only if it contains the full response as expected
            if (array_key_exists('shortDescription', $data)
                || (array_key_exists('series', $data) && count($data['series']))
            ) {
                $this->cacheStore->put($cacheKey, $data, now()->addHour()->getTimestamp());
            }
        }

        return $data;
    }

    private function getCacheKey(bool $highlight, string $latitude, string $longitude, int $id): string
    {
        return sprintf("%s_%s_%s_%s", $id, (int) $highlight, $longitude, $latitude);
    }
}
