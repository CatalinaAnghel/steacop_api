<?php
declare(strict_types=1);

namespace App\State\Processor\MilestoneMeeting;

use ApiPlatform\Metadata\Operation;
use App\Dto\Meeting\Input\CreateMeetingInputDto;
use App\Dto\Meeting\Output\MilestoneMeetingOutputDto;
use App\Entity\MilestoneMeeting;
use App\Entity\Project;
use App\State\Processor\Contracts\AbstractMilestoneMeetingProcessor;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateMilestoneMeetingStateProcessor extends AbstractMilestoneMeetingProcessor
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger)
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param CreateMeetingInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?MilestoneMeetingOutputDto
    {
        $projectRepo = $this->entityManager->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $data->getProjectId()]);
        if (null !== $project) {
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(CreateMeetingInputDto::class,
                    MilestoneMeeting::class);
                $mapper = new AutoMapper($config);
                /**
                 * @var MilestoneMeeting $meeting
                 */
                $meeting = $mapper->map($data, MilestoneMeeting::class);
                $meeting->setProject($project);
                $meeting->setUpdatedAt(new \DateTime('Now'));
                $meeting->setCreatedAt(new \DateTimeImmutable('Now'));
                $meeting->setIsCompleted(false);
                $meeting->setIsCanceled(false);
                $meeting->setIsMissed(false);
                $meeting->setScheduledAt($data->getScheduledAt());
                $this->entityManager->persist($meeting);
                $this->entityManager->flush();

                $mapper = $this->getMapper();

                /**
                 * @var MilestoneMeetingOutputDto $meetingDto
                 */
                $meetingDto = $mapper->map($meeting, MilestoneMeetingOutputDto::class);
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
