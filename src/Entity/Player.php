<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $ip = null;


    /**
     * @var Collection<int, PoolCompletion>
     */
    #[ORM\OneToMany(targetEntity: PoolCompletion::class, mappedBy: 'player')]
    private Collection $poolCompletions;
 
    public function __construct()
    {
        $this->poolCompletions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

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
            $poolCompletion->setPlayer($this);
        }

        return $this;
    }

    public function removePoolCompletion(PoolCompletion $poolCompletion): static
    {
        if ($this->poolCompletions->removeElement($poolCompletion)) {
            // set the owning side to null (unless already changed)
            if ($poolCompletion->getPlayer() === $this) {
                $poolCompletion->setPlayer(null);
            }
        }

        return $this;
    }
}
