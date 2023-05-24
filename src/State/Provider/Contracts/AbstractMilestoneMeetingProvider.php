<?php
declare(strict_types=1);

namespace App\State\Provider\Contracts;

use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\MilestoneMeetingOutputDto;
use App\Entity\MilestoneMeeting;
use App\State\Common\MapperInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

abstract class AbstractMilestoneMeetingProvider implements ProviderInterface, MapperInterface
{
    public function getMapper(): AutoMapper
    {
        $configOutput = new AutoMapperConfig();
        $configOutput->registerMapping(
            MilestoneMeeting::class,
            MilestoneMeetingOutputDto::class
        )
            ->forMember('studentFullName', function (MilestoneMeeting $milestoneMeeting): string {
                $student = $milestoneMeeting->getProject()?->getStudent();
                return $student?->getFirstName() . ' ' . $student?->getLastName();
            })
            ->forMember('supervisorFullName', function (MilestoneMeeting $milestoneMeeting): string {
                $supervisor = $milestoneMeeting->getProject()?->getSupervisor();
                return $supervisor?->getFirstName() . ' ' . $supervisor?->getLastName();
            });
        return new AutoMapper($configOutput);
    }
}
