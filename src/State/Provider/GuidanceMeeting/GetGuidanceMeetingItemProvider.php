<?php
declare(strict_types=1);

namespace App\State\Provider\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\Entity\GuidanceMeeting;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetGuidanceMeetingItemProvider implements ProviderInterface {
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly LoggerInterface   $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null {
        $guidanceMeeting = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $guidanceMeeting) {
            $configOutput = new AutoMapperConfig();
            $configOutput->registerMapping(
                GuidanceMeeting::class,
                GuidanceMeetingOutputDto::class
            );
            $mapper = new AutoMapper($configOutput);

            try {
                /**
                 * @var GuidanceMeetingOutputDto $meetingDto
                 */
                $guidanceMeetingDto = $mapper->map($guidanceMeeting, GuidanceMeetingOutputDto::class);
            } catch (UnregisteredMappingException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $guidanceMeetingDto ?? null;
    }
}
