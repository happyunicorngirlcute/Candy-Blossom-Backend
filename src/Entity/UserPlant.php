<?php

namespace App\Entity;

use App\Repository\UserPlantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiProperty;
#[ORM\Entity(repositoryClass: UserPlantRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/user/plant',
            security: "is_granted('ROLE_USER')",
            processor: \App\State\SetCurrentUserProcessor::class
        ),
        new GetCollection(
            uriTemplate: '/user/plants', 
            security: "is_granted('ROLE_USER')",
            normalizationContext: ['groups' => ['plant:read']]
        ),
    ]
)]
class UserPlant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['plant:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userPlants')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(writable: false)]
    private ?User $user = null;


    #[ORM\Column(nullable: true)]
    #[ApiProperty(writable: false)]
    private ?\DateTimeImmutable $nextWateringAt = null;

    #[ORM\ManyToOne(inversedBy: 'userPlants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['plant:read'])]
    private ?Plant $plant = null;

    #[ORM\Column(length: 255)]
    #[Groups(['plant:read'])]
    private ?string $city = null;

    #[ORM\Column(length: 2048, nullable: true)]
    #[Groups(['plant:read'])]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    #[Groups(['plant:read'])]
    public function getNextWateringAt(): ?\DateTimeImmutable
    {
        return $this->nextWateringAt;
    }
    public function setNextWateringAt(?\DateTimeImmutable $date): static
    {
        $this->nextWateringAt = $date;
        return $this;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    #[Groups(['plant:read'])]
    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): static
    {
        $this->plant = $plant;
        return $this;
    }

    #[Groups(['plant:read'])]
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    #[Groups(['plant:read'])]
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }
}
