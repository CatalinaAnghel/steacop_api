<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SupervisorImportFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: SupervisorImportFileRepository::class)]
#[ApiResource]
class SupervisorImportFile {
    /**
     * @var int|null $id
     */
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null $contentUrl
     */
    private ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "supervisor_import_file", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['supervisor_import_file:input'])]
    #[Assert\File(mimeTypes: ['text/csv'])]
    private ?File $file = null;

    /**
     * @var string|null $filePath
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    /**
     * @var \DateTimeImmutable|null $createdAt
     */
    #[ORM\Column(nullable: true)]
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
     * @return File|null
     */
    public function getFile(): ?File {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return void
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
     * @return void
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
}
