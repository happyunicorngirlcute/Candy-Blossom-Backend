<?php

namespace App\Controller;

use App\Entity\Plant;
use App\Entity\User;
use App\Entity\UserPlant;
use App\Repository\PlantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Service\WateringCalculatorService;
use App\Service\PerenualApiService;


use App\Service\PlantMapperService;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


final class PlantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WateringCalculatorService $wateringCalculator,
        private PlantMapperService $plantMapper,
        private SluggerInterface $slugger
    ) {}

    #[Route('/user/plant/{id}/image', name: 'user_upload_plant_image', methods: ['POST'])]
    public function uploadPlantImage(UserPlant $userPlant, Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user || $userPlant->getUser() !== $user) {
            return $this->json(['message' => 'Unauthorized'], 403);
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('image');

        if (!$file) {
            return $this->json(['message' => 'No image provided'], 400);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads/plants',
                $newFilename
            );
        } catch (\Exception $e) {
            return $this->json(['message' => 'Failed to upload image'], 500);
        }

        $baseUrl = $request->getSchemeAndHttpHost();
        $imageUrl = $baseUrl . '/uploads/plants/' . $newFilename;

        $userPlant->setImage($imageUrl);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Image uploaded successfully!',
            'image_url' => $imageUrl
        ]);
    }

    // =========================
    // GET ALL PLANTS
    // =========================
    #[Route('/plants', name: 'get_plants', methods: ['GET'])]
    public function index(PlantRepository $plantRepository): JsonResponse
    {
        $plants = $plantRepository->findAll();

        return $this->json([
            'message' => 'Here is the list of all the plants I know',
            'data' => $plants
        ], 200, [], [
            'groups' => ['plant:read']
        ]);
    }

    // =========================
    // CREATE PLANT
    // =========================
    #[Route('/plants', name: 'create_plant', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['common_name']) || !isset($data['watering_general_benchmark'])) {
            return $this->json([
                'message' => 'I need the plant name and its watering benchmark to add it to my database!'
            ], 400);
        }

        $plant = new Plant();
        $plant->setCommonName($data['common_name']);
        $plant->setWateringGeneralBenchmark($data['watering_general_benchmark']);

        $this->entityManager->persist($plant);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'I have added the plant to my database!',
            'data' => $plant
        ], 201, [], [
            'groups' => ['plant:read']
        ]);
    }


    #[Route('/user/plant/{id}', name: 'user_delete_plant', methods: ['DELETE'])]
    public function deletePlantFromUser(UserPlant $userPlant): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'I could not identify you, make sure you are logged in and send a valid token'
            ], 401);
        }

        assert($user instanceof \App\Entity\User);

        // Vérifier que la plante appartient bien à l'utilisateur connecté
        if ($userPlant->getUser() !== $user) {
            return $this->json([
                'message' => 'You are not the owner of this plant'
            ], 403);
        }

        $this->entityManager->remove($userPlant);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }

    // =========================
    // GET PLANT BY NAME
    // =========================
    #[Route('/plant/name/{name}', name: 'get_plant_by_name', methods: ['GET'])]
    public function showPlantByName(string $name, PlantRepository $plantRepository): JsonResponse
    {
        $plant = $plantRepository->findOneBy([
            'common_name' => $name
        ]);

        if (!$plant) {
            return $this->json([
                'message' => 'I could not find the plant you are looking for, check the spelling'
            ], 404);
        }

        return $this->json([
            'message' => 'I found the plant you were looking for',
            'data' => $plant
        ], 200, [], [
            'groups' => ['plant:read']
        ]);
    }

    #[Route('/user/plants', name: 'get_plant_by_user', methods: ['GET'])]
    public function showPlantByUser(): JsonResponse
    {
        // 1. Auth check EN PREMIER
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'I could not identify you, make sure you are logged in'
            ], 401);
        }

        assert($user instanceof \App\Entity\User);

        // 2. Récupère les UserPlants de l'utilisateur connecté
        $userPlants = $user->getUserPlants();

        // 3. Recalculate watering dates based on latest weather for "automatic" updates
        foreach ($userPlants as $userPlant) {
            try {
                $newNextWatering = $this->wateringCalculator->calculate($userPlant->getPlant(), $userPlant->getCity());
                $userPlant->setNextWateringAt($newNextWatering);
            } catch (\Exception $e) {
                // If weather API fails, keep the old date
            }
        }
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Here are the plants you have in your collection (updated with latest weather)',
            'data' => $userPlants
        ], 200, [], [
            'groups' => ['plant:read']
        ]);
    }
    // =========================
    // DELETE PLANT
    // =========================
    #[Route('/plant/{id}', name: 'delete_plant', methods: ['DELETE'])]
    public function delete(int $id, PlantRepository $plantRepository): JsonResponse
    {
        $plant = $plantRepository->find($id);

        if (!$plant) {
            return $this->json([
                'message' => 'I could not find the plant you want to delete, check the spelling'
            ], 404);
        }

        $this->entityManager->remove($plant);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }

    // =========================
    // USER ADD PLANT (WITH CITY)
    // =========================
    #[Route('/user/plant', name: 'user_add_plant', methods: ['POST'])]
    public function addPlantToUser(Request $request, PlantRepository $plantRepository, PerenualApiService $perenualApiService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Not authenticated'], 401);
        }

        assert($user instanceof \App\Entity\User);

        $data = json_decode($request->getContent(), true);

        // Validate that we have enough info
        if (!isset($data['common_name'], $data['city'])) {
            return $this->json(['message' => 'I need the name of the plant and the city!'], 400);
        }

        $plant = $plantRepository->findOneBy(['common_name' => $data['common_name']]);

        // If not in our DB, create it
        if (!$plant) {
            // If the frontend passed an ID from Perenual, we use it to get full details
            if (isset($data['perenual_id'])) {
                try {
                    $details = $perenualApiService->getPlantDetails($data['perenual_id']);
                    $plant = new Plant();
                    $this->plantMapper->map($plant, $details);
                    $this->entityManager->persist($plant);
                } catch (\Exception $e) {
                    return $this->json(['message' => 'Failed to fetch plant details from API'], 500);
                }
            } else {
                // Fallback to what frontend sent (minimal)
                $plant = new Plant();
                $plant->setCommonName($data['common_name']);
                $plant->setWatering($data['watering'] ?? null);
                $plant->setSunlight($data['sunlight'] ?? null);
                $plant->setImage($data['image'] ?? null);
                $plant->setWateringGeneralBenchmark($data['watering_general_benchmark'] ?? []);
                $this->entityManager->persist($plant);
            }
        }

        // Final check: if after fetching we still miss critical info, we stop
        // Relaxed: we now allow plants even with missing info, using defaults in calculator
        if (!$plant->getWatering()) {
            $plant->setWatering('Average'); // Default
        }
        
        if (empty($plant->getSunlight())) {
            $plant->setSunlight(['Part shade']); // Default
        }

        foreach ($user->getUserPlants() as $userPlant) {
            if ($userPlant->getPlant() === $plant) {
                return $this->json(['message' => 'You already have this plant!'], 409);
            }
        }

        // Calcule le prochain arrosage
        $nextWatering = $this->wateringCalculator->calculate($plant, $data['city']);

        $userPlant = new UserPlant();
        $userPlant->setUser($user);
        $userPlant->setPlant($plant);
        $userPlant->setCity($data['city']);
        $userPlant->setNextWateringAt($nextWatering);

        $this->entityManager->persist($userPlant);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'I have added the plant to your collection!',
            'data' => [
                'plant' => $plant->getCommonName(),
                'city' => $data['city'],
                'next_watering_at' => $nextWatering->format('Y-m-d'),
                'days_until_watering' => (new \DateTimeImmutable())->diff($nextWatering)->days
            ]
        ], 201);
    }
}
