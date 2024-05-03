<?php

namespace App\Entity\Calendar;

use App\Repository\Calendar\ShowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShowRepository::class)]
#[ORM\Table(name: '`show`')]
class Show
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?\DateTimeImmutable $showtime = null;

    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?\DateInterval $trailersDuration = null;

    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?\DateInterval $presentationDuration = null;

    #[ORM\Column]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?\DateInterval $debateDuration = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?Movie $movie = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private ?Screen $screen = null;

    #[ORM\ManyToMany(targetEntity: Version::class)]
    #[Groups(["shows_get_item", "shows_get_collection"])]
    private Collection $versions;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShowtime(): ?\DateTimeImmutable
    {
        return $this->showtime;
    }

    public function setShowtime(\DateTimeImmutable $Showtime): self
    {
        $this->showtime = $Showtime;

        return $this;
    }


    public function getTrailersDuration(): ?\DateInterval
    {
        return $this->trailersDuration;
    }

    public function setTrailersDuration(\DateInterval$trailersDuration): self
    {
        $this->trailersDuration = $trailersDuration;

        return $this;
    }

    public function getPresentationDuration(): ?\DateInterval
    {
        return $this->presentationDuration;
    }

    public function setPresentationDuration(\DateInterval $presentationDuration): self
    {
        $this->presentationDuration = $presentationDuration;

        return $this;
    }

    public function getDebateDuration(): ?\DateInterval
    {
        return $this->debateDuration;
    }

    public function setDebateDuration(\DateInterval $debateDuration): self
    {
        $this->debateDuration = $debateDuration;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getScreen(): ?Screen
    {
        return $this->screen;
    }

    public function setScreen(?Screen $screen): self
    {
        $this->screen = $screen;

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
