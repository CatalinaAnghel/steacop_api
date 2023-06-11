<?php
declare(strict_types=1);

namespace App\Message\Event\Functionality;

use App\Entity\Functionality;
use App\Message\Event\Functionality\Contracts\AbstractFunctionalityEvent;

class FunctionalityCreatedEvent extends AbstractFunctionalityEvent
{
    /**
     * @param Functionality $functionality
     * @param string[] $receivers
     */
    public function __construct(Functionality $functionality, array $receivers)
    {
        parent::__construct($functionality, $receivers);
    }
}