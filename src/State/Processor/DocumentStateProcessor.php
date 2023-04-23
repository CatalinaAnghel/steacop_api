<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Assignment;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface     $decorated,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @inheritDoc
     * @param Document $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Document
    {
        $assignmentRepo = $this->entityManager->getRepository(Assignment::class);
        $assignment = $assignmentRepo->findOneBy(['id' => (int)$data->getAssignmentId()]);
        if (null === $assignment) {
            throw new NotFoundHttpException('The assignment could not be found');
        }
        $data->setAssignment($assignment);
        $this->decorated->process($data, $operation, $uriVariables, $context);
        $data->setContentUrl('/documents/assignments/' . $assignment->getId() . '/' . $data->getFilePath());

        return $data;
    }
}
