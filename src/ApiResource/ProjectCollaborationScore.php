<?php
declare(strict_types=1);

namespace App\ApiResource;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class ProjectCollaborationScore
{
    /**
     * @var int $projectId
     */
    private int $projectId;

    /**
     * @var BaseScore $ratingScore
     */
    private BaseScore $ratingScore;

    /**
     * @var BaseScore $supportScore
     */
    private BaseScore $supportScore;

    /**
     * @var BaseScore $structureScore
     */
    private BaseScore $structureScore;

    /**
     * @var float $score
     */
    private float $score;

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     * @return ProjectCollaborationScore
     */
    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    /**
     * @return BaseScore
     */
    public function getRatingScore(): BaseScore
    {
        return $this->ratingScore;
    }

    /**
     * @param BaseScore $ratingScore
     * @return ProjectCollaborationScore
     */
    public function setRatingScore(BaseScore $ratingScore): self
    {
        $this->ratingScore = $ratingScore;
        return $this;
    }

    /**
     * @return BaseScore
     */
    public function getSupportScore(): BaseScore
    {
        return $this->supportScore;
    }

    /**
     * @param BaseScore $supportScore
     * @return ProjectCollaborationScore
     */
    public function setSupportScore(BaseScore $supportScore): self
    {
        $this->supportScore = $supportScore;
        return $this;
    }

    /**
     * @return BaseScore
     */
    public function getStructureScore(): BaseScore
    {
        return $this->structureScore;
    }

    /**
     * @param BaseScore $structureScore
     * @return ProjectCollaborationScore
     */
    public function setStructureScore(BaseScore $structureScore): self
    {
        $this->structureScore = $structureScore;
        return $this;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @param float $score
     * @return ProjectCollaborationScore
     */
    public function setScore(float $score): ProjectCollaborationScore
    {
        $this->score = $score;
        return $this;
    }
}
