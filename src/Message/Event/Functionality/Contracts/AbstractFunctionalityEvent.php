<?php
declare(strict_types=1);

namespace App\Message\Event\Functionality\Contracts;

use App\Entity\Functionality;

abstract class AbstractFunctionalityEvent
{
    /**
     * @param Functionality $functionality
     * @param string[] $receivers
     */
    public function __construct(private readonly Functionality $functionality, private readonly array $receivers)
    {
    }

    /**
     * @return Functionality
     */
    public function getFunctionality(): Functionality
    {
        return $this->functionality;
    }

    /**
     * @return string[]
     */
    public function getReceivers(): array
    {
        return $this->receivers;
    }
}