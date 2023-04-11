<?php
declare(strict_types=1);

namespace App\State\Processor\Rating;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Rating\Output\RatingOutputDto;
use App\Entity\Rating;
use App\Entity\User;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class PatchRatingStateProcessor implements ProcessorInterface {
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $logger,
        private readonly Security               $security,
        private readonly ValidatorInterface     $ratingValidator
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?RatingOutputDto {
        $ratingRepo = $this->entityManager->getRepository(Rating::class);
        $rating = $ratingRepo->findOneBy(['id' => $uriVariables['id']]);
        if (null !== $rating) {
            $this->ratingValidator->validate($rating);
            try {

                $userRepo = $this->entityManager->getRepository(User::class);
                $user = $userRepo->findOneBy(['email' => $this->security->getUser()?->getUserIdentifier()]);

                $rating->setValue($data->getValue());
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(Rating::class, RatingOutputDto::class);
                $mapper = new AutoMapper($configOutput);

                /**
                 * @var RatingOutputDto $ratingDto
                 */
                $ratingDto = $mapper->map($rating, RatingOutputDto::class);
                $ratingDto->setGuidanceMeetingId($rating->getId());
                $ratingDto->setUserCode($user?->getCode());
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        } else {
            throw new NotFoundHttpException('The rating could not be found');
        }

        return $ratingDto ?? null;
    }
}
