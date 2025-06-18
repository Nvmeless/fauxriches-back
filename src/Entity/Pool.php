<?php

namespace App\Entity;

use App\Repository\PoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: PoolRepository::class)]
class Pool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    /**
     * @var Collection<int, Song>
     */
    #[ORM\ManyToMany(targetEntity: Song::class, inversedBy: 'pools')]
    private Collection $songs;

    #[ORM\ManyToOne]
    private ?DownloadedFile $picture = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getSongs"])]
    private ?string $name = null;

    /**
     * @var Collection<int, PoolCompletion>
     */
    #[ORM\OneToMany(targetEntity: PoolCompletion::class, mappedBy: 'pool', orphanRemoval: true)]
    private Collection $poolCompletions;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getSongs"])]
    private ?string $edition = null;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
        $this->poolCompletions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): static
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
        }

        return $this;
    }

    public function removeSong(Song $song): static
    {
        $this->songs->removeElement($song);

        return $this;
    }

    public function getPicture(): ?DownloadedFile
    {
        return $this->picture;
    }

    public function setPicture(?DownloadedFile $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, PoolCompletion>
     */
    public function getPoolCompletions(): Collection
    {
        return $this->poolCompletions;
    }

    public function addPoolCompletion(PoolCompletion $poolCompletion): static
    {
        if (!$this->poolCompletions->contains($poolCompletion)) {
            $this->poolCompletions->add($poolCompletion);
            $poolCompletion->setPool($this);
        }

        return $this;
    }

    public function removePoolCompletion(PoolCompletion $poolCompletion): static
    {
        if ($this->poolCompletions->removeElement($poolCompletion)) {
            // set the owning side to null (unless already changed)
            if ($poolCompletion->getPool() === $this) {
                $poolCompletion->setPool(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getEdition(): ?string
    {
        return $this->edition;
    }

    public function setEdition(?string $edition): static
    {
        $this->edition = $edition;

        return $this;
    }
}
