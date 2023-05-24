<?php
declare(strict_types=1);

namespace App\State\Provider\GuidanceMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\GuidanceMeetingOutputDto;
use App\State\Provider\Contracts\AbstractGuidanceMeetingProvider;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetGuidanceMeetingItemProvider extends AbstractGuidanceMeetingProvider
{
    public function __construct(private readonly ProviderInterface $decoratedProvider,
                                private readonly LoggerInterface   $logger
    )
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $guidanceMeeting = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $guidanceMeeting) {
            $mapper = $this->getMapper();
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
