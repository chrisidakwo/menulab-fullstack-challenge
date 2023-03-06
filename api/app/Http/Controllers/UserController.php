<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'users' => User::query()->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     *
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function getUserWeatherDetails(Request $request, User $user): JsonResponse
    {
        $highlight = $request->boolean('highlight');

        $weatherData = $this->userService->getWeatherData($highlight, $user->latitude, $user->longitude, $user);

        return response()->json([
            'weather' => $weatherData,
            'user' => $user,
        ]);
    }
}
