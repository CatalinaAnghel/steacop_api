<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaObject;
use App\Service\Contract\UploadFileServiceInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[AsController]
final class CreateMediaObjectAction extends AbstractController {
    public function __construct(private readonly UploadFileServiceInterface $uploadFileService) {}

    public function __invoke(Request $request): MediaObject {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        return $this->uploadFileService->upload($uploadedFile);
    }
}
