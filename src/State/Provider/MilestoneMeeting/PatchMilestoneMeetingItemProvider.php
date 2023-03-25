<?php
declare(strict_types=1);

namespace App\State\Provider\MilestoneMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Input\PatchMilestoneMeetingInputDto;
use App\Entity\MilestoneMeeting;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchMilestoneMeetingItemProvider implements ProviderInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $meetingDto = null;
        if (isset($uriVariables['id'])) {
            $milestoneMeetingRepo = $this->entityManager->getRepository(MilestoneMeeting::class);
            $meeting = $milestoneMeetingRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($meeting !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(MilestoneMeeting::class,
                    PatchMilestoneMeetingInputDto::class
                );
                $mapper = new AutoMapper($config);
                try{
                    $meetingDto = $mapper->map($meeting, PatchMilestoneMeetingInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $meetingDto;
    }
}
