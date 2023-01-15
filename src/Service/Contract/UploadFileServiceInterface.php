<?php
declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\MediaObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadFileServiceInterface {
    /**
     * @param UploadedFile $uploadedFile
     * @return MediaObject
     */
    public function upload(UploadedFile $uploadedFile): MediaObject;
}