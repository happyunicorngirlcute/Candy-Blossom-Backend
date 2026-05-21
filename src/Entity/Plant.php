<?php

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiProperty;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/plants', normalizationContext: ['groups' => ['plant:read']]),
        new Get(uriTemplate: '/plants/{id}', normalizationContext: ['groups' => ['plant:read']]),
        new Get(uriTemplate: '/plant/name/{name}', normalizationContext: ['groups' => ['plant:read']]),
        new Post(uriTemplate: '/plants', security: "is_granted('ROLE_USER')"),
        new Delete(uriTemplate: '/plant/{id}', security: "is_granted('ROLE_USER')"),
    ]
)]
class Plant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(writable: false)]
    #[Groups(['plant:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['plant:read'])]
    private array $watering_general_benchmark = [];

    #[ORM\Column(length: 255)]
    #[Groups(['plant:read'])]
    private ?string $common_name = null;

    #[ORM\OneToMany(mappedBy: 'plant', targetEntity: UserPlant::class)]
    private Collection $userPlants;

    #[ORM\Column(length: 2048, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $sunlight = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $watering = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $scientific_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $family = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $type = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $dimensions = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $cycle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $maintenance = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $growth_rate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $care_level = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $watering_quality = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $watering_period = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $sunlight_duration = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $pruning_month = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $attracts = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $propagation = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $hardiness = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $flowers = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $flowering_season = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $medicinal = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $poisonous_to_humans = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $poisonous_to_pets = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?int $perenual_id = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $other_name = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $origin = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $plant_anatomy = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $pruning_count = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?int $seeds = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $hardiness_location = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $soil = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $pest_susceptibility = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $cones = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $fruits = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $edible_fruit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $fruiting_season = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $harvest_season = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $harvest_method = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $leaf = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $edible_leaf = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $drought_tolerant = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $salt_tolerant = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $thorny = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $invasive = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $rare = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $tropical = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $cuisine = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['plant:read'])]
    private ?bool $indoor = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['plant:read'])]
    private ?array $other_images = null;

    public function __construct()
    {
        $this->userPlants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerenualId(): ?int
    {
        return $this->perenual_id;
    }

    public function setPerenualId(?int $perenual_id): static
    {
        $this->perenual_id = $perenual_id;
        return $this;
    }

    public function getOtherName(): ?array
    {
        return $this->other_name;
    }

    public function setOtherName(?array $other_name): static
    {
        $this->other_name = $other_name;
        return $this;
    }

    public function getOrigin(): ?array
    {
        return $this->origin;
    }

    public function setOrigin(?array $origin): static
    {
        $this->origin = $origin;
        return $this;
    }

    public function getPlantAnatomy(): ?array
    {
        return $this->plant_anatomy;
    }

    public function setPlantAnatomy(?array $plant_anatomy): static
    {
        $this->plant_anatomy = $plant_anatomy;
        return $this;
    }

    public function getPruningCount(): ?array
    {
        return $this->pruning_count;
    }

    public function setPruningCount(?array $pruning_count): static
    {
        $this->pruning_count = $pruning_count;
        return $this;
    }

    public function getSeeds(): ?int
    {
        return $this->seeds;
    }

    public function setSeeds(?int $seeds): static
    {
        $this->seeds = $seeds;
        return $this;
    }

    public function getHardinessLocation(): ?array
    {
        return $this->hardiness_location;
    }

    public function setHardinessLocation(?array $hardiness_location): static
    {
        $this->hardiness_location = $hardiness_location;
        return $this;
    }

    public function getSoil(): ?array
    {
        return $this->soil;
    }

    public function setSoil(?array $soil): static
    {
        $this->soil = $soil;
        return $this;
    }

    public function getPestSusceptibility(): ?array
    {
        return $this->pest_susceptibility;
    }

    public function setPestSusceptibility(?array $pest_susceptibility): static
    {
        $this->pest_susceptibility = $pest_susceptibility;
        return $this;
    }

    public function isCones(): ?bool
    {
        return $this->cones;
    }

    public function setCones(?bool $cones): static
    {
        $this->cones = $cones;
        return $this;
    }

    public function isFruits(): ?bool
    {
        return $this->fruits;
    }

    public function setFruits(?bool $fruits): static
    {
        $this->fruits = $fruits;
        return $this;
    }

    public function isEdibleFruit(): ?bool
    {
        return $this->edible_fruit;
    }

    public function setEdibleFruit(?bool $edible_fruit): static
    {
        $this->edible_fruit = $edible_fruit;
        return $this;
    }

    public function getFruitingSeason(): ?string
    {
        return $this->fruiting_season;
    }

    public function setFruitingSeason(?string $fruiting_season): static
    {
        $this->fruiting_season = $fruiting_season;
        return $this;
    }

    public function getHarvestSeason(): ?string
    {
        return $this->harvest_season;
    }

    public function setHarvestSeason(?string $harvest_season): static
    {
        $this->harvest_season = $harvest_season;
        return $this;
    }

    public function getHarvestMethod(): ?string
    {
        return $this->harvest_method;
    }

    public function setHarvestMethod(?string $harvest_method): static
    {
        $this->harvest_method = $harvest_method;
        return $this;
    }

    public function isLeaf(): ?bool
    {
        return $this->leaf;
    }

    public function setLeaf(?bool $leaf): static
    {
        $this->leaf = $leaf;
        return $this;
    }

    public function isEdibleLeaf(): ?bool
    {
        return $this->edible_leaf;
    }

    public function setEdibleLeaf(?bool $edible_leaf): static
    {
        $this->edible_leaf = $edible_leaf;
        return $this;
    }

    public function isDroughtTolerant(): ?bool
    {
        return $this->drought_tolerant;
    }

    public function setDroughtTolerant(?bool $drought_tolerant): static
    {
        $this->drought_tolerant = $drought_tolerant;
        return $this;
    }

    public function isSaltTolerant(): ?bool
    {
        return $this->salt_tolerant;
    }

    public function setSaltTolerant(?bool $salt_tolerant): static
    {
        $this->salt_tolerant = $salt_tolerant;
        return $this;
    }

    public function isThorny(): ?bool
    {
        return $this->thorny;
    }

    public function setThorny(?bool $thorny): static
    {
        $this->thorny = $thorny;
        return $this;
    }

    public function isInvasive(): ?bool
    {
        return $this->invasive;
    }

    public function setInvasive(?bool $invasive): static
    {
        $this->invasive = $invasive;
        return $this;
    }

    public function isRare(): ?bool
    {
        return $this->rare;
    }

    public function setRare(?bool $rare): static
    {
        $this->rare = $rare;
        return $this;
    }

    public function isTropical(): ?bool
    {
        return $this->tropical;
    }

    public function setTropical(?bool $tropical): static
    {
        $this->tropical = $tropical;
        return $this;
    }

    public function isCuisine(): ?bool
    {
        return $this->cuisine;
    }

    public function setCuisine(?bool $cuisine): static
    {
        $this->cuisine = $cuisine;
        return $this;
    }

    public function isIndoor(): ?bool
    {
        return $this->indoor;
    }

    public function setIndoor(?bool $indoor): static
    {
        $this->indoor = $indoor;
        return $this;
    }

    public function getOtherImages(): ?array
    {
        return $this->other_images;
    }

    public function setOtherImages(?array $other_images): static
    {
        $this->other_images = $other_images;
        return $this;
    }

    public function getWateringGeneralBenchmark(): array
    {
        return $this->watering_general_benchmark;
    }

    public function setWateringGeneralBenchmark(array $watering_general_benchmark): static
    {
        $this->watering_general_benchmark = $watering_general_benchmark;
        return $this;
    }

    public function getCommonName(): ?string
    {
        return $this->common_name;
    }

    public function setCommonName(string $common_name): static
    {
        $this->common_name = $common_name;
        return $this;
    }

    public function getUserPlants(): Collection
    {
        return $this->userPlants;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getSunlight(): ?array
    {
        return $this->sunlight;
    }

    public function setSunlight(?array $sunlight): static
    {
        $this->sunlight = $sunlight;

        return $this;
    }

    public function getWatering(): ?string
    {
        return $this->watering;
    }

    public function setWatering(?string $watering): static
    {
        $this->watering = $watering;

        return $this;
    }

    public function getScientificName(): ?array
    {
        return $this->scientific_name;
    }

    public function setScientificName(?array $scientific_name): static
    {
        $this->scientific_name = $scientific_name;
        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(?string $family): static
    {
        $this->family = $family;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): static
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function getCycle(): ?string
    {
        return $this->cycle;
    }

    public function setCycle(?string $cycle): static
    {
        $this->cycle = $cycle;
        return $this;
    }

    public function getMaintenance(): ?string
    {
        return $this->maintenance;
    }

    public function setMaintenance(?string $maintenance): static
    {
        $this->maintenance = $maintenance;
        return $this;
    }

    public function getGrowthRate(): ?string
    {
        return $this->growth_rate;
    }

    public function setGrowthRate(?string $growth_rate): static
    {
        $this->growth_rate = $growth_rate;
        return $this;
    }

    public function getCareLevel(): ?string
    {
        return $this->care_level;
    }

    public function setCareLevel(?string $care_level): static
    {
        $this->care_level = $care_level;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getWateringQuality(): ?array
    {
        return $this->watering_quality;
    }

    public function setWateringQuality(?array $watering_quality): static
    {
        $this->watering_quality = $watering_quality;
        return $this;
    }

    public function getWateringPeriod(): ?array
    {
        return $this->watering_period;
    }

    public function setWateringPeriod(?array $watering_period): static
    {
        $this->watering_period = $watering_period;
        return $this;
    }

    public function getSunlightDuration(): ?array
    {
        return $this->sunlight_duration;
    }

    public function setSunlightDuration(?array $sunlight_duration): static
    {
        $this->sunlight_duration = $sunlight_duration;
        return $this;
    }

    public function getPruningMonth(): ?array
    {
        return $this->pruning_month;
    }

    public function setPruningMonth(?array $pruning_month): static
    {
        $this->pruning_month = $pruning_month;
        return $this;
    }

    public function getAttracts(): ?array
    {
        return $this->attracts;
    }

    public function setAttracts(?array $attracts): static
    {
        $this->attracts = $attracts;
        return $this;
    }

    public function getPropagation(): ?array
    {
        return $this->propagation;
    }

    public function setPropagation(?array $propagation): static
    {
        $this->propagation = $propagation;
        return $this;
    }

    public function getHardiness(): ?array
    {
        return $this->hardiness;
    }

    public function setHardiness(?array $hardiness): static
    {
        $this->hardiness = $hardiness;
        return $this;
    }

    public function isFlowers(): ?bool
    {
        return $this->flowers;
    }

    public function setFlowers(?bool $flowers): static
    {
        $this->flowers = $flowers;
        return $this;
    }

    public function getFloweringSeason(): ?string
    {
        return $this->flowering_season;
    }

    public function setFloweringSeason(?string $flowering_season): static
    {
        $this->flowering_season = $flowering_season;
        return $this;
    }

    public function isMedicinal(): ?bool
    {
        return $this->medicinal;
    }

    public function setMedicinal(?bool $medicinal): static
    {
        $this->medicinal = $medicinal;
        return $this;
    }

    public function isPoisonousToHumans(): ?bool
    {
        return $this->poisonous_to_humans;
    }

    public function setPoisonousToHumans(?bool $poisonous_to_humans): static
    {
        $this->poisonous_to_humans = $poisonous_to_humans;
        return $this;
    }

    public function isPoisonousToPets(): ?bool
    {
        return $this->poisonous_to_pets;
    }

    public function setPoisonousToPets(?bool $poisonous_to_pets): static
    {
        $this->poisonous_to_pets = $poisonous_to_pets;
        return $this;
    }
}
