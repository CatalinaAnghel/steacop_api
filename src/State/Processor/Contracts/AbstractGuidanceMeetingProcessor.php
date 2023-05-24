<?php
declare(strict_types=1);

namespace App\State\Processor\Contracts;

use ApiPlatform\State\ProcessorInterface;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use App\State\Common\MapperInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;

abstract class AbstractGuidanceMeetingProcessor implements ProcessorInterface, MapperInterface
{
    public function getMapper(): AutoMapper{
        $configOutput = new AutoMapperConfig();
        $configOutput->registerMapping(
            GuidanceMeeting::class,
            GuidanceMeetingOutputDto::class
        )
            ->forMember('studentFullName', function (GuidanceMeeting $guidanceMeeting): string {
                $student = $guidanceMeeting->getProject()?->getStudent();
                return $student?->getFirstName() . ' ' . $student?->getLastName();
            })
            ->forMember('supervisorFullName', function (GuidanceMeeting $guidanceMeeting): string {
                $supervisor = $guidanceMeeting->getProject()?->getSupervisor();
                return $supervisor?->getFirstName() . ' ' . $supervisor?->getLastName();
            });
        return new AutoMapper($configOutput);
    }
}
