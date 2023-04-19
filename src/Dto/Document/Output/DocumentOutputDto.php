<?php
declare(strict_types=1);

namespace App\Dto\Document\Output;

class DocumentOutputDto {
    private string $contentUrl;
    private \DateTimeImmutable $createdAt;
    private string $filePath;
    private int $id;

    /**
     * @return string
     */
    public function getContentUrl(): string {
        return $this->contentUrl;
    }

    /**
     * @param string $contentUrl
     */
    public function setContentUrl(string $contentUrl): void {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTimeImmutable {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getFilePath(): string {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void {
        $this->filePath = $filePath;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }
}
