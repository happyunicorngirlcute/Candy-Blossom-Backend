<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherApiService
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function getWeather(string $city): array
    {
        $response = $this->httpClient->request('GET', 'http://api.weatherapi.com/v1/current.json', [
            'query' => [
                'key' => $_ENV['WEATHER_API_KEY'],
                'q' => $city,
                'lang' => 'fr'
            ]
        ]);

        return $response->toArray();
    }
}