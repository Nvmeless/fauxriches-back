<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DownloadedFileRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DownloadedFileRepository::class)]
class DownloadedFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $realPath = null;

    #[ORM\Column(length: 255)]
    private ?string $publicPath = null;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    #[ORM\Column(length: 24)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $realName = null;

    #[Vich\UploadableField(mapping:"uploadedFiles", fileNameProperty:'realPath', size:"fileSize")]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $fileSize = null;
    public function getFile(): ?File
    {
        return $this->file; 

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealPath(): ?string
    {
        return $this->realPath;
    }

    public function setRealPath(string $realPath): static
    {
        $this->realPath = $realPath;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return $this->publicPath;
    }

    public function setPublicPath(string $publicPath): static
    {
        $this->publicPath = $publicPath;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setFile(File $file):self
    {
        $this->file = $file;
        return $this;
    }
    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(string $realName): static
    {
        $this->realName = $realName;

        return $this;
    }

    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }

    public function setFileSize(string $fileSize): static
    {
        $this->fileSize = $fileSize;

        return $this;
    }
}
