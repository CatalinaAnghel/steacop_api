<?php
declare(strict_types=1);

namespace App\State\Processor\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use App\Dto\Meeting\Input\PatchGuidanceMeetingInputDto;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use App\Message\Command\GuidanceMeeting\UpdateGuidanceMeetingCommand;
use App\State\Processor\Contracts\AbstractGuidanceMeetingProcessor;
use App\Validator\Contracts\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PatchGuidanceMeetingStateProcessor extends AbstractGuidanceMeetingProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus,
        private readonly ValidatorInterface $meetingValidator
    ) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     * @param PatchGuidanceMeetingInputDto $data
     */
    public function process(
        mixed $data, Operation $operation, array $uriVariables = [], array $context = []
    ): ?GuidanceMeetingOutputDto {
        $guidanceMeetingRepo = $this->entityManager->getRepository(GuidanceMeeting::class);
        $guidanceMeeting = $guidanceMeetingRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $guidanceMeeting) {
            $this->meetingValidator->validate($data, $guidanceMeeting);

            try {
                $this->commandBus->dispatch(
                    new UpdateGuidanceMeetingCommand(
                        $data,
                        $guidanceMeeting,
                        $guidanceMeeting->getProject()
                    )
                );
                $this->entityManager->flush();

                $mapper = $this->getMapper();
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
