<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection", "movies_get_collection"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["shows_get_item", "shows_get_collection", "movies_get_collection"])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(["shows_get_collection", "movies_get_collection"])]
    private ?\DateInterval $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["shows_get_collection"])]
    private ?string $poster = null;


    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Show::class)]
    #[ORM\OrderBy(["showtime" => "ASC"])]
    private Collection $shows;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $releaseDate = null;

    #[ORM\ManyToMany(targetEntity: ScreeningSchedule::class, mappedBy: 'movies', cascade: ['persist'])]
    private Collection $screeningSchedules;

    #[ORM\ManyToMany(targetEntity: Version::class)]
    private Collection $versions;


    public function __construct()
    {
        $this->shows = new ArrayCollection();
        $this->screeningSchedules = new ArrayCollection();
        $this->versions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection<int, Show>
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Show $show): self
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
            $show->setMovie($this);
        }

        return $this;
    }

    public function removeShow(Show $show): self
    {
        if ($this->shows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getMovie() === $this) {
                $show->setMovie(null);
            }
        }

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeImmutable $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection<int, ScreeningSchedule>
     */
    public function getScreeningSchedules(): Collection
    {
        return $this->screeningSchedules;
    }

    public function addScreeningSchedule(ScreeningSchedule $screeningSchedule): self
    {
        if (!$this->screeningSchedules->contains($screeningSchedule)) {
            $this->screeningSchedules->add($screeningSchedule);
            $screeningSchedule->addMovie($this);
        }

        return $this;
    }

    public function removeScreeningSchedule(ScreeningSchedule $screeningSchedule): self
    {
        if ($this->screeningSchedules->removeElement($screeningSchedule)) {
            $screeningSchedule->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        $this->versions->removeElement($version);

        return $this;
    }

}
