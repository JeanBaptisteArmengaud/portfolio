<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\ScreenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ScreenRepository::class)]
class Screen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $capacity = null;

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

}
