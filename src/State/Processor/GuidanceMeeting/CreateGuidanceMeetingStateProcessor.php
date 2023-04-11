<?php
declare(strict_types=1);

namespace App\State\Processor\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Meeting\Input\CreateMeetingInputDto;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use App\Entity\Project;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateGuidanceMeetingStateProcessor implements ProcessorInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param CreateMeetingInputDto $data
     * @return ?GuidanceMeetingOutputDto
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?GuidanceMeetingOutputDto {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $data->getProjectId()]);
        if (null !== $project) {
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(CreateMeetingInputDto::class,
                    GuidanceMeeting::class);
                $mapper = new AutoMapper($config);
                /**
                 * @var GuidanceMeeting $meeting
                 */
                $meeting = $mapper->map($data, GuidanceMeeting::class);
                $meeting->setProject($project);
                $meeting->setUpdatedAt(new \DateTime('Now'));
                $meeting->setCreatedAt(new \DateTimeImmutable('Now'));
                $meeting->setIsCompleted(false);
                $meeting->setIsCanceled(false);
                $this->entityManager->persist($meeting);
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    GuidanceMeeting::class,
                    GuidanceMeetingOutputDto::class
                );
                $mapper = new AutoMapper($configOutput);

                /**
                 * @var GuidanceMeetingOutputDto $meetingDto
                 */
                $meetingDto = $mapper->map($meeting, GuidanceMeetingOutputDto::class);
                $meetingDto->setScheduledAt(new \DateTime(($meeting->getScheduledAt())?->format('Y-m-d H:i:s')));
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        } else {
            throw new NotFoundHttpException('The project could not be found');
        }

        return $meetingDto ?? null;
    }
}
