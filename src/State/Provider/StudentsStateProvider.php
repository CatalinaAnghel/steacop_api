<?php
declare(strict_types=1);

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Project\Output\ProjectDto;
use App\Dto\Shared\Output\DepartmentDto;
use App\Dto\Shared\Output\SpecializationDto;
use App\Dto\Student\Output\StudentDto;
use App\Dto\User\Output\UserDto;
use App\Entity\Student;
use App\Paginator\StatePaginator;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class StudentsStateProvider implements ProviderInterface
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
        $students = $this->decoratedProvider->provide($operation, $uriVariables, $context);
        $config = new AutoMapperConfig();
        $config
            ->registerMapping(Student::class, StudentDto::class)
            ->forMember('project', function (Student $student): ProjectDto|null {
                $project = $student->getProject();
                if ($project !== null) {
                    $dto = new ProjectDto();
                    $dto->setTitle($project->getTitle());
                    $dto->setId($project->getId());
                    $dto->setDescription($project->getDescription());
                    return $dto;
                }
                return null;
            })
            ->forMember('user', function (Student $student): UserDto|null {
                $user = $student->getUser();
                if ($user !== null) {
                    $userDto = new UserDto();
                    $userDto->setCode($user->getCode());
                    $userDto->setEmail($user->getEmail());
                    return $userDto;
                }

                return null;
            })
            ->forMember('specialization', function (Student $student): SpecializationDto|null {
                $specialization = $student->getSpecialization();
                if ($specialization !== null) {
                    $specializationDto = new SpecializationDto();
                    $specializationDto->setName($specialization->getName());
                    $specializationDto->setId($specialization->getId());
                    $departmentDto = new DepartmentDto();
                    $departmentDto->setId($specialization->getDepartment()?->getId());
                    $departmentDto->setName($specialization->getDepartment()?->getName());
                    $specializationDto->setDepartment($departmentDto);
                    return $specializationDto;
                }
                return null;
            });
        $mapper = new AutoMapper($config);
        $studentsCollection = $mapper->mapMultiple($students, StudentDto::class);
        $studentIterator = new \ArrayIterator($studentsCollection);

        [$page, $offset, $limit] = $this->pagination->getPagination(
            $operation, $context);
        $max = count($this->entityManager->getRepository(Student::class)->findAll());
        return new StatePaginator($studentIterator, $page, $limit, $max);
    }
}
