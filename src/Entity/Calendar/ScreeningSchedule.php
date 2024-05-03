<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\ScreeningScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ScreeningScheduleRepository::class)]
#[UniqueEntity(fields: ['weekStart', 'weekEnd'])]
class ScreeningSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $weekStart = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $weekEnd = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, inversedBy: 'screeningSchedules')]
    #[Groups(["movies_get_collection"])]
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekStart(): ?\DateTimeImmutable
    {
        return $this->weekStart;
    }

    public function setWeekStart(\DateTimeImmutable $weekStart): self
    {
        $this->weekStart = $weekStart;

        return $this;
    }

    public function getWeekEnd(): ?\DateTimeImmutable
    {
        return $this->weekEnd;
    }

    public function setWeekEnd(\DateTimeImmutable $weekEnd): self
    {
        $this->weekEnd = $weekEnd;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        $this->movies->removeElement($movie);

        return $this;
    }
}
