<?php

namespace App\Service;

use App\Entity\Plant;

class WateringCalculatorService
{
    public function __construct(
        private WeatherApiService $weatherApiService
    ) {}

    public function calculate(Plant $plant, string $city): \DateTimeImmutable
    {
        // Récupère la météo
        $weather = $this->weatherApiService->getWeather($city);
        $temp = $weather['current']['temp_c'];
        $humidity = $weather['current']['humidity'];
        $isRaining = $weather['current']['precip_mm'] > 0;

        // Récupère l'intervalle de base depuis la plante
        $benchmark = $plant->getWateringGeneralBenchmark();
        $baseDays = $this->getBaseDays($plant->getWatering(), $benchmark);

        // Ajuste selon la météo
        if ($isRaining) {
            $baseDays += 2; // Il pleut → on repousse
        }
        if ($temp > 30) {
            $baseDays -= 2; // Très chaud → on avance
        }
        if ($humidity < 30) {
            $baseDays -= 1; // Très sec → on avance
        }

        // Minimum 1 jour
        $baseDays = max(1, $baseDays);

        return new \DateTimeImmutable('+' . $baseDays . ' days');
    }

    private function getBaseDays(string $watering = null, array $benchmark = []): int
    {
        // Utilise le benchmark si disponible
        if (!empty($benchmark['value'])) {
            $value = $benchmark['value'];
            // "7-10" → prend la moyenne
            if (str_contains($value, '-')) {
                [$min, $max] = explode('-', $value);
                return (int)(((int)$min + (int)$max) / 2);
            }
            return (int)$value;
        }

        // Sinon utilise le champ watering
        return match ($watering) {
            'Frequent'  => 2,
            'Average'   => 7,
            'Minimum'   => 14,
            'None'      => 30,
            default     => 7
        };
    }
}
