<?php
declare(strict_types=1);

namespace App\State\Provider\MilestoneMeeting;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Meeting\Output\MilestoneMeetingOutputDto;
use App\State\Provider\Contracts\AbstractMilestoneMeetingProvider;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Psr\Log\LoggerInterface;

class GetMilestoneMeetingItemProvider extends AbstractMilestoneMeetingProvider
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
        $milestoneMeeting = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        if (null !== $milestoneMeeting) {
            $mapper = $this->getMapper();

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
