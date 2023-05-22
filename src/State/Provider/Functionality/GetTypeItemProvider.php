<?php
declare(strict_types=1);

namespace App\State\Provider\Functionality;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Output\TypeOutputDto;
use App\State\Provider\Contracts\AbstractTypeProvider;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class GetTypeItemProvider extends AbstractTypeProvider
{
    public function __construct(
        private readonly ProviderInterface      $decoratedProvider,
        private readonly LoggerInterface        $logger,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $types = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $mapper = $this->getMapper();
        try {
            /**
             * @var TypeOutputDto $functionalityDto
             */
            $typeDto = $mapper->map($types, TypeOutputDto::class);
        } catch (UnregisteredMappingException $e) {
            $this->logger->error($e->getMessage());
        }

        return $typeDto ?? null;
    }
}
