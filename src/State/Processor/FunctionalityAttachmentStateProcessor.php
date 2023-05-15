<?php
declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Document;
use App\Entity\Functionality;
use App\Entity\FunctionalityAttachment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FunctionalityAttachmentStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface     $decorated,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @inheritDoc
     * @param FunctionalityAttachment $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FunctionalityAttachment
    {
        $functionalityRepo = $this->entityManager->getRepository(Functionality::class);
        $functionality = $functionalityRepo->findOneBy(['id' => (int)$data->getFunctionalityId()]);
        if (null === $functionality) {
            throw new NotFoundHttpException('The item could not be found');
        }
        $data->setFunctionality($functionality);
        $data->setCreatedAt(new \DateTimeImmutable('Now'));
        $this->decorated->process($data, $operation, $uriVariables, $context);
        $data->setContentUrl('/documents/functionalities/' . $functionality->getId() . '/' . $data->getFilePath());

        return $data;
    }
}
