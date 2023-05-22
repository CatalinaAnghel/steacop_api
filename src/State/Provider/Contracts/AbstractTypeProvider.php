<?php
declare(strict_types=1);

namespace App\State\Provider\Contracts;

use ApiPlatform\State\ProviderInterface;
use App\Dto\Functionality\Output\TypeOutputDto;
use App\Entity\FunctionalityType;
use App\Helper\FunctionalityTypesHelper;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractTypeProvider implements ProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function getMapper(): AutoMapper{
        $config = new AutoMapperConfig();
        $config->registerMapping(FunctionalityType::class, TypeOutputDto::class)
            ->forMember('possibleChildTypes', function (FunctionalityType $source): array {
                $possibleChildIssues = [];
                $childIssues = FunctionalityTypesHelper::tryFrom($source->getName())?->getPossibleChildIssues();
                $repo = $this->entityManager->getRepository(FunctionalityType::class);
                foreach ($childIssues as $childIssue) {
                    $foundIssueType = $repo->findOneBy(['name' => $childIssue]);
                    if (null !== $foundIssueType) {
                        $possibleChildIssues[] = $foundIssueType->getId();
                    }
                }
                return $possibleChildIssues;
            });
        return new AutoMapper($config);
    }
}
