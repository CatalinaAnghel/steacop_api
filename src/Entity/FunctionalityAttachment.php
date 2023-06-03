<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FunctionalityAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: FunctionalityAttachmentRepository::class)]
#[ApiResource]
class FunctionalityAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'functionalityAttachments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Functionality $functionality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    private string $functionalityId = "";

    /**
     * @var string|null $contentUrl
     */
    private ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "functionality_attachment_upload", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['functionality_attachment_upload:input'])]
    #[Assert\File(mimeTypes: ['application/pdf', 'image/jpeg', 'image/png'])]
    private ?File $file = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFunctionality(): ?Functionality
    {
        return $this->functionality;
    }

    public function setFunctionality(?Functionality $functionality): self
    {
        $this->functionality = $functionality;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getFunctionalityId(): string
    {
        return $this->functionalityId;
    }

    /**
     * @param string $functionalityId
     */
    public function setFunctionalityId(string $functionalityId): void
    {
        $this->functionalityId = $functionalityId;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void
    {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}
