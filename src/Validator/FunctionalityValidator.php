<?php

namespace App\Validator;

use ApiPlatform\Validator\Exception\ValidationException;
use App\Dto\Functionality\Input\Contracts\AbstractFunctionalityInputDto;
use App\Dto\Functionality\Input\PatchFunctionalityInputDto;
use App\Entity\Functionality;
use App\Entity\FunctionalityStatus;
use App\Entity\FunctionalityType;
use App\Helper\FunctionalityTypesHelper;
use App\Validator\Contracts\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class FunctionalityValidator implements ValidatorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public const FUNCTIONALITY_INVALID_TYPE = 'Invalid type';
    public const FUNCTIONALITY_INVALID_STATUS = 'Invalid status';

    /**
     * @param AbstractFunctionalityInputDto $data
     * @param Functionality $referencedObject
     * @param string|null $operation
     * @return void
     */
    public function validate($data, $referencedObject = null, string $operation = null): void
    {
        if ($referencedObject instanceof PatchFunctionalityInputDto) {
            /**
             * @var $data PatchFunctionalityInputDto
             */
            $status = $this->entityManager->getRepository(FunctionalityStatus::class)
                ->findOneBy(['id' => $data->getStatus()]);
            if (null === $status) {
                throw new ValidationException(self::FUNCTIONALITY_INVALID_STATUS);
            }
        }

        $type = $this->entityManager->getRepository(FunctionalityType::class)
            ->findOneBy(['id' => $data->getType()]);
        if (null === $type) {
            throw new ValidationException(self::FUNCTIONALITY_INVALID_TYPE);
        }

        if (null !== $data->getParentFunctionalityId()) {
            $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
            $parentFunctionality = $functionalityRepo->findOneBy(['id' => $data->getParentFunctionalityId()]);
            if (null === $parentFunctionality ||
                (null !== $referencedObject && $parentFunctionality->getId() === $referencedObject->getId())) {
                throw new ValidationException('Invalid parent functionality');
            }
            $validationMessage = $this->validateParentFunctionality($type, $parentFunctionality->getType());
            if (null !== $validationMessage) {
                throw new ValidationException($validationMessage);
            }
        }
    }

    private function validateParentFunctionality(FunctionalityType $childType, FunctionalityType $parentType): ?string
    {
        $type = FunctionalityTypesHelper::tryFrom($parentType->getName());
        $possibleChildIssues = $type?->getPossibleChildIssues();
        $valid = null !== $type && in_array($childType->getName(), $possibleChildIssues, true);

        return !$valid ? 'Cannot add a ' . $childType->getName() . ' to a ' . $parentType->getName() : null;
    }
}
