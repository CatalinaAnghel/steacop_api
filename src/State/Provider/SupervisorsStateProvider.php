<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Shared\Output\DepartmentDto;
use App\Dto\Supervisor\Output\SupervisorDto;
use App\Dto\User\Output\UserDto;
use App\Entity\Supervisor;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class SupervisorsStateProvider implements ProviderInterface
{
    public function __construct(private readonly ProviderInterface      $decoratedProvider,
                                private readonly Pagination             $pagination,
                                private readonly EntityManagerInterface $entityManager) {}

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $supervisors = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(Supervisor::class, SupervisorDto::class)
            ->forMember('user', function (Supervisor $supervisor): UserDto|null {
                $user = $supervisor->getUser();
                if ($user !== null) {
                    $userDto = new UserDto();
                    $userDto->setCode($user->getCode());
                    $userDto->setEmail($user->getEmail());
                    return $userDto;
                }

                return null;
            })
            ->forMember('department', function (Supervisor $supervisor): DepartmentDto|null {
                $department = $supervisor->getDepartment();
                if ($department !== null) {
                    $departmentDto = new DepartmentDto();
                    $departmentDto->setId($department->getId());
                    $departmentDto->setName($department->getName());
                    return $departmentDto;
                }
                return null;
            });
        $mapper = new AutoMapper($config);
        $supervisorsCollection = $mapper->mapMultiple($supervisors, SupervisorDto::class);
        $supervisorsIterator = new \ArrayIterator($supervisorsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($this->entityManager->getRepository(Supervisor::class)->findAll());
        return new StatePaginator($supervisorsIterator, $page, $limit, $max);
    }
}
