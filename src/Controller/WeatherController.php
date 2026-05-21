<?php

namespace App\Controller;

use App\Service\WeatherApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class WeatherController extends AbstractController
{
    public function __construct(
        private WeatherApiService $weatherApiService
    ) {}

    #[Route('/weather/{city}', name: 'get_weather', methods: ['GET'])]
    public function getWeather(string $city): JsonResponse
    {
        $data = $this->weatherApiService->getWeather($city);

        return $this->json([
            'city' => $data['location']['name'],
            'temperature' => $data['current']['temp_c'],
            'condition' => $data['current']['condition']['text'],
        ]);
    }
}