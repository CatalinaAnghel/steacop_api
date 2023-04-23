<?php
declare(strict_types=1);

namespace App\State\Provider\Rating;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Rating\Input\RatingInputDto;
use App\Entity\Rating;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PatchRatingProvider implements ProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger) {}

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $ratingDto = null;
        if (isset($uriVariables['id'])) {
            $ratingRepo = $this->entityManager->getRepository(Rating::class);
            $rating = $ratingRepo->findOneBy(['id' => (int)$uriVariables['id']]);
            if ($rating !== null) {
                $config = new AutoMapperConfig();
                $config->registerMapping(Rating::class,
                    RatingInputDto::class
                );
                $mapper = new AutoMapper($config);
                try {
                    $ratingDto = $mapper->map($rating, RatingInputDto::class);

                } catch (UnregisteredMappingException $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        }
        return $ratingDto;
    }
}
