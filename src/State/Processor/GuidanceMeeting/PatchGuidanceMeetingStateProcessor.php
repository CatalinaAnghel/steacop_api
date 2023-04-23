<?php
declare(strict_types=1);

namespace App\State\Processor\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Meeting\Input\PatchGuidanceMeetingInputDto;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchGuidanceMeetingStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger,
                                private readonly ValidatorInterface     $meetingValidator
    )
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param PatchGuidanceMeetingInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?GuidanceMeetingOutputDto
    {
        $guidanceMeetingRepo = $this->entityManager->getRepository(GuidanceMeeting::class);
        $guidanceMeeting = $guidanceMeetingRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $guidanceMeeting) {
            $this->meetingValidator->validate($data, $guidanceMeeting);
            $guidanceMeeting->setDescription($data->getDescription());
            $guidanceMeeting->setDuration($data->getDuration());
            $guidanceMeeting->setScheduledAt($data->getScheduledAt());
            $guidanceMeeting->setLink($data->getLink());
            $guidanceMeeting->setUpdatedAt(new \DateTime('Now'));
            $guidanceMeeting->setIsCompleted($data->getIsCompleted());
            if ($data->getIsCanceled()) {
                $guidanceMeeting->setIsCanceled(true);
                $guidanceMeeting->setCanceledAt(new \DateTimeImmutable('Now'));
            }
            $this->entityManager->persist($guidanceMeeting);

            try {
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    GuidanceMeeting::class,
                    GuidanceMeetingOutputDto::class);
                $mapper = new AutoMapper($configOutput);
                /**
                 * @var GuidanceMeetingOutputDto $meetingDto
                 */
                $meetingDto = $mapper->map($guidanceMeeting, GuidanceMeetingOutputDto::class);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $meetingDto ?? null;
    }
}
