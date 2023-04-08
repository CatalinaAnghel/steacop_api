<?php
declare(strict_types=1);

namespace App\State\Provider\MilestoneMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\MilestoneMeetingOutputDto;
use App\Entity\MilestoneMeeting;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetMilestoneMeetingItemProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly LoggerInterface   $logger
    ) {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $milestoneMeeting = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $milestoneMeeting) {
            $configOutput = new AutoMapperConfig();
            $configOutput->registerMapping(
                MilestoneMeeting::class,
                MilestoneMeetingOutputDto::class
            );
            $mapper = new AutoMapper($configOutput);

            try {
                /**
                 * @var MilestoneMeetingOutputDto $meetingDto
                 */
                $milestoneMeetingDto = $mapper->map($milestoneMeeting, MilestoneMeetingOutputDto::class);
            } catch (UnregisteredMappingException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $milestoneMeetingDto ?? null;
    }
}
