<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Pool>
     */
    #[ORM\ManyToMany(targetEntity: Pool::class, mappedBy: 'songs')]
    #[Groups(["getSongs"])]
    private Collection $pools;
    #[Groups(["getSongs"])]
    private ?string $url = null;


    #[Groups(["getSongs"])]
    #[ORM\ManyToOne]
    private ?DownloadedFile $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getSongs"])]
    private ?int $rarity = null;

    public function __construct()
    {
        $this->pools = new ArrayCollection();
    }
    #[Groups(["getSongs"])]
    public function getId(): ?int
    {
        return $this->id;
    }
    #[Groups(["getSongs"])]

    public function getName(): ?string
    {
        return $this->name;
    }

    #[Groups(["getSongs"])]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
    public function getUrl(): ?string
    {
        return $this->url;
    }
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
    /**
     * @return Collection<int, Pool>
     */
    public function getPools(): Collection
    {
        return $this->pools;
    }

    public function addPool(Pool $pool): static
    {
        if (!$this->pools->contains($pool)) {
            $this->pools->add($pool);
            $pool->addSong($this);
        }

        return $this;
    }

    public function removePool(Pool $pool): static
    {
        if ($this->pools->removeElement($pool)) {
            $pool->removeSong($this);
        }

        return $this;
    }

    public function getFile(): ?DownloadedFile
    {
        return $this->file;
    }

    public function setFile(?DownloadedFile $file): static
    {
        $this->file = $file;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getRarity(): ?int
    {
        return $this->rarity;
    }

    public function setRarity(?int $rarity): static
    {
        $this->rarity = $rarity;

        return $this;
    }
}
