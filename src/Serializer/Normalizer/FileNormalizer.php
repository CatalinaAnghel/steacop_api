<?php
declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Document;
use App\Entity\StudentImportFile;
use App\Entity\SupervisorImportFile;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

final class FileNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'FILE_NORMALIZER_ALREADY_CALLED';

    public function __construct(private readonly StorageInterface $storage) {}

    public function normalize($object, ?string $format = null, array $context = []):
    array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        $object->setContentUrl($this->storage->resolveUri($object, 'file'));
        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof StudentImportFile || $data instanceof SupervisorImportFile || $data instanceof Document;
    }
}
