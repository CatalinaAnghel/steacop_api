<?php
declare(strict_types=1);

namespace App\State\Processor\MilestoneMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Meeting\Input\PatchMilestoneMeetingInputDto;
use App\Dto\Meeting\Output\MilestoneMeetingOutputDto;
use App\Entity\MilestoneMeeting;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchMilestoneMeetingProcessor implements ProcessorInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger,
                                private readonly ValidatorInterface     $meetingValidator
    ) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param PatchMilestoneMeetingInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?MilestoneMeetingOutputDto {
        $milestoneMeetingRepo = $this->entityManager->getRepository(MilestoneMeeting::class);
        $milestoneMeeting = $milestoneMeetingRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $milestoneMeeting) {
            $this->meetingValidator->validate($data, $milestoneMeeting);
            $milestoneMeeting->setDescription($data->getDescription());
            $milestoneMeeting->setScheduledAt($data->getScheduledAt());
            $milestoneMeeting->setLink($data->getLink());
            $milestoneMeeting->setDuration($data->getDuration());
            if (null !== $data->getGrade()) {
                $milestoneMeeting->setGrade($data->getGrade());
            }
            $milestoneMeeting->setUpdatedAt(new \DateTime('Now'));
            $milestoneMeeting->setIsCompleted($data->getIsCompleted());
            if($data->getIsCanceled()){
                $milestoneMeeting->setIsCanceled(true);
                $milestoneMeeting->setCanceledAt(new \DateTimeImmutable('Now'));
            }
            $this->entityManager->persist($milestoneMeeting);

            try {
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(
                    MilestoneMeeting::class,
                    MilestoneMeetingOutputDto::class);
                $mapper = new AutoMapper($configOutput);
                /**
                 * @var MilestoneMeetingOutputDto $meetingDto
                 */
                $meetingDto = $mapper->map($milestoneMeeting, MilestoneMeetingOutputDto::class);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $meetingDto ?? null;
    }
}
