<?php
declare(strict_types=1);

namespace App\Message\Command\Functionality;

use App\Entity\Functionality;
use App\Entity\Project;

class CreateFunctionalityCommand
{
    public function __construct(
        private readonly Functionality $functionality,
        private readonly Project    $project
    ) {}

    /**
     * @return Functionality
     */
    public function getFunctionality(): Functionality
    {
        return $this->functionality;
    }



    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
}
