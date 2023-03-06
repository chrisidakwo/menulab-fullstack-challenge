<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function getUserWeatherDetails(Request $request, User $user, string $coordinate): JsonResponse
    {
        [$latitude, $longitude] = explode(',', trim(str_replace(' ', '', $coordinate)));

        $highlight = $request->boolean('highlight');

        $weatherData = $this->userService->getWeatherData($highlight, $latitude, $longitude, $user);

        return response()->json([
            'data' => $weatherData,
        ]);
    }
}
