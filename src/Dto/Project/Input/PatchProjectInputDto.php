<?php
declare(strict_types=1);

namespace App\Dto\Project\Input;

use Symfony\Component\Validator\Constraints as Assert;

class PatchProjectInputDto
{
    /**
     * @var string|null $title
     */
    #[Assert\Length(min: 20, max: 255)]
    private ?string $title = null;

    /**
     * @var string|null $description
     */
    #[Assert\Length(min:20)]
    private ?string $description = null;

    /**
     * @var string|null $repositoryUrl
     */
    private ?string $repositoryUrl = null;

    /**
     * @var float|null $grade
     */
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(10)]
    private ?float $grade = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getRepositoryUrl(): ?string
    {
        return $this->repositoryUrl;
    }

    /**
     * @param string|null $repositoryUrl
     */
    public function setRepositoryUrl(?string $repositoryUrl): void
    {
        $this->repositoryUrl = $repositoryUrl;
    }

    /**
     * @return float|null
     */
    public function getGrade(): ?float
    {
        return $this->grade;
    }

    /**
     * @param float|null $grade
     */
    public function setGrade(?float $grade): void
    {
        $this->grade = $grade;
    }
}
