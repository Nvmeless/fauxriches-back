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
    private ?bool $done = null;
    /**
     * @var Collection<int, Pool>
     */
    #[ORM\ManyToMany(targetEntity: Pool::class, mappedBy: 'songs')]
    private Collection $pools;

    public function __construct()
    {
        $this->pools = new ArrayCollection();
    }
 #[Groups([ "getSongs"])]
    public function getId(): ?int
    {
        return $this->id;
    }
 #[Groups([ "getSongs"])]

    public function getName(): ?string
    {
        return $this->name;
    }
 #[Groups([ "getSongs"])]

 public function getDone( ): ?bool{
   return $this->done;
 }
 #[Groups([ "getSongs"])]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
   public function setDone(bool $done): static
    {
        $this->done = $done;

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
}
