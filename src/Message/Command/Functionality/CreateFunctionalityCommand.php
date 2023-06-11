<?php
declare(strict_types=1);

namespace App\Message\Command\Functionality;

use App\Entity\Functionality;
use App\Entity\Project;
use App\Message\Command\Functionality\Contracts\AbstractFunctionalityCommand;

class CreateFunctionalityCommand extends AbstractFunctionalityCommand
{
    public function __construct(
        Functionality $functionality,
        Project    $project
    ) {
        parent::__construct($functionality, $project);
    }
}
