<?php

namespace App\Controller;

use App\Service\PerenualApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PlantApiController extends AbstractController
{
    public function __construct(
        private PerenualApiService $perenualApiService
    ) {}

    #[Route('/api/plants/search/{name}/{page}', name: 'search_plant', methods: ['GET'], defaults: ['page' => 1])]
    public function searchPlant(string $name, int $page): JsonResponse
    {
        try {
            $data = $this->perenualApiService->searchPlant($name, $page);

            return $this->json([
                'data' => $data['data'] ?? [],
                'current_page' => $data['current_page'] ?? 1,
                'last_page' => $data['last_page'] ?? 1,
                'total' => $data['total'] ?? 0
            ]);
        } catch (\Exception $e) {
            $statusCode = 500;
            if (str_contains($e->getMessage(), '429') || str_contains($e->getMessage(), 'rate limit')) {
                $statusCode = 429;
            }
            return $this->json([
                'error' => $e->getMessage(),
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0
            ], $statusCode);
        }
    }

    #[Route('/api/plants/details/{id}', name: 'plant_details', methods: ['GET'])]
    public function getPlantDetails(int $id): JsonResponse
    {
        try {
            $data = $this->perenualApiService->getPlantDetails($id);

            return $this->json([
                'data' => $data
            ]);
        } catch (\Exception $e) {
            $statusCode = 500;
            if (str_contains($e->getMessage(), '429') || str_contains($e->getMessage(), 'rate limit')) {
                $statusCode = 429;
            }
            return $this->json([
                'error' => $e->getMessage(),
                'data' => null
            ], $statusCode);
        }
    }
}