<?php

namespace App\Entity;

use App\Repository\PoolCompletionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoolCompletionRepository::class)]
class PoolCompletion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'poolCompletions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pool $pool = null;

    #[ORM\ManyToOne(inversedBy: 'poolCompletions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Song $song = null;


    public function getId(): ?int
    {
        return $this->id;
    }
   
    public function getPool(): ?Pool
    {
        return $this->pool;
    }
    public function setPool(?Pool $pool): static
    {
        $this->pool = $pool;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): static
    {
        $this->song = $song;

        return $this;
    }


}
