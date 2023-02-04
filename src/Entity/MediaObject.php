<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[ApiResource]
class MediaObject {
    /**
     * @var int|null $id
     */
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * @var string|null $contentUrl
     */
    private ?string $contentUrl = null;

    /**
     * @var File|null $file
     */
    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['media_object:input'])]
    private ?File $file = null;

    /**
     * @var string|null $filePath
     */
    #[ORM\Column(nullable: true)]
    private ?string $filePath = null;

    /**
     * @var \DateTimeImmutable|null $createdAt
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string {
        return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void {
        $this->filePath = $filePath;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }
}
