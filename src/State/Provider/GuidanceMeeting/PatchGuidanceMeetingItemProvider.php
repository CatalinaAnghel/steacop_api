<?php
declare(strict_types=1);

namespace App\State\Provider\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Input\PatchGuidanceMeetingInputDto;
use App\Entity\GuidanceMeeting;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchGuidanceMeetingItemProvider implements ProviderInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $meetingDto = null;
        if (isset($uriVariables['id'])) {
            $guidanceMeetingRepo = $this->entityManager->getRepository(GuidanceMeeting::class);
            $meeting = $guidanceMeetingRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($meeting !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(GuidanceMeeting::class,
                    PatchGuidanceMeetingInputDto::class
                );
                $mapper = new AutoMapper($config);
                try{
                    $meetingDto = $mapper->map($meeting, PatchGuidanceMeetingInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $meetingDto;
    }
}
