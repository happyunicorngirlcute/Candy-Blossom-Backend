<?php

namespace App\Service;

use App\Entity\Plant;

class PlantMapperService
{
    public function map(Plant $plant, array $details): Plant
    {
        $plant->setPerenualId($details['id'] ?? null);
        $plant->setCommonName($details['common_name'] ?? 'Unknown');
        $plant->setScientificName($this->getValue($details, 'scientific_name'));
        $plant->setOtherName($this->getValue($details, 'other_name'));
        $plant->setFamily($details['family'] ?? null);
        $plant->setOrigin($this->getValue($details, 'origin'));
        $plant->setType($details['type'] ?? null);
        $plant->setDimensions($this->getValue($details, 'dimensions'));
        $plant->setCycle($details['cycle'] ?? null);
        $plant->setWatering($details['watering'] ?? null);
        $plant->setWateringGeneralBenchmark($this->getValue($details, 'watering_general_benchmark', []));
        $plant->setPlantAnatomy($this->getValue($details, 'plant_anatomy'));
        $plant->setSunlight($this->getValue($details, 'sunlight'));
        $plant->setPruningMonth($this->getValue($details, 'pruning_month'));
        $plant->setPruningCount($this->getValue($details, 'pruning_count'));
        $plant->setSeeds($details['seeds'] ?? null);
        $plant->setAttracts($this->getValue($details, 'attracts'));
        $plant->setPropagation($this->getValue($details, 'propagation'));
        $plant->setHardiness($this->getValue($details, 'hardiness'));
        $plant->setHardinessLocation($this->getValue($details, 'hardiness_location'));
        $plant->setFlowers($details['flowers'] ?? null);
        $plant->setFloweringSeason($details['flowering_season'] ?? null);
        $plant->setSoil($this->getValue($details, 'soil'));
        $plant->setPestSusceptibility($this->getValue($details, 'pest_susceptibility'));
        $plant->setCones($details['cones'] ?? null);
        $plant->setFruits($details['fruits'] ?? null);
        $plant->setEdibleFruit($details['edible_fruit'] ?? null);
        $plant->setFruitingSeason($details['fruiting_season'] ?? null);
        $plant->setHarvestSeason($details['harvest_season'] ?? null);
        $plant->setHarvestMethod($details['harvest_method'] ?? null);
        $plant->setLeaf($details['leaf'] ?? null);
        $plant->setEdibleLeaf($details['edible_leaf'] ?? null);
        $plant->setGrowthRate($details['growth_rate'] ?? null);
        $plant->setMaintenance($details['maintenance'] ?? null);
        $plant->setMedicinal($details['medicinal'] ?? null);
        $plant->setPoisonousToHumans($details['poisonous_to_humans'] ?? null);
        $plant->setPoisonousToPets($details['poisonous_to_pets'] ?? null);
        $plant->setDroughtTolerant($details['drought_tolerant'] ?? null);
        $plant->setSaltTolerant($details['salt_tolerant'] ?? null);
        $plant->setThorny($details['thorny'] ?? null);
        $plant->setInvasive($details['invasive'] ?? null);
        $plant->setRare($details['rare'] ?? null);
        $plant->setTropical($details['tropical'] ?? null);
        $plant->setCuisine($details['cuisine'] ?? null);
        $plant->setIndoor($details['indoor'] ?? null);
        $plant->setCareLevel($details['care_level'] ?? null);
        $plant->setDescription($details['description'] ?? null);
        
        $image = $details['default_image']['regular_url'] ?? 
                 $details['default_image']['original_url'] ?? 
                 $details['default_image']['thumbnail'] ?? null;
        $plant->setImage($image);

        $plant->setOtherImages($this->getValue($details, 'other_images'));

        return $plant;
    }

    /**
     * Helper to get a value from the details array, with a fallback to "Supreme User" keys
     * and ensuring it matches the expected type (array).
     */
    private function getValue(array $details, string $key, $default = null)
    {
        $value = $details[$key] ?? $details[$key . ' Supreme User '] ?? null;
        
        // Special case for fields that must be arrays
        if ($default === [] || in_array($key, ['scientific_name', 'other_name', 'origin', 'dimensions', 'watering_general_benchmark', 'plant_anatomy', 'sunlight', 'pruning_month', 'pruning_count', 'attracts', 'propagation', 'hardiness', 'hardiness_location', 'soil', 'pest_susceptibility', 'other_images'])) {
            return is_array($value) ? $value : $default;
        }

        return $value ?? $default;
    }

    private function ensureArray($value): ?array
    {
        if (is_array($value)) {
            return $value;
        }
        return null;
    }
}
