<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileService implements Contract\UploadFileServiceInterface {
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     */
    public function upload(UploadedFile $uploadedFile): MediaObject {
        $mediaObject = new MediaObject();
        $mediaObject->setFile($uploadedFile);
        $mediaObject->setFilePath('/media/' . $uploadedFile->getFilename());
        $this->entityManager->persist($mediaObject);
        $this->entityManager->flush();
        return $mediaObject;
    }
}
