<?php
declare(strict_types=1);

namespace App\State\Provider\Rating;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Rating\Output\RatingOutputDto;
use App\Entity\Rating;
use App\Entity\User;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class GetRatingItemProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security               $security,
        private readonly ValidatorInterface     $ratingValidator,
        private readonly LoggerInterface        $logger,
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @inheritDoc
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $ratingRepo = $this->entityManager->getRepository(Rating::class);
        $userRepo = $this->entityManager->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => $this->security->getUser()?->getUserIdentifier()]);
        $rating = $ratingRepo->findOneBy([
            'meeting' => $uriVariables['meetingId'],
            'user' => $user?->getId()
        ]);

        if (null !== $rating) {
            $this->ratingValidator->validate($rating);
            $config = new AutoMapperConfig();
            $config->registerMapping(
                Rating::class,
                RatingOutputDto::class
            )
                ->forMember('guidanceMeetingId', function (Rating $source): int {
                    return $source->getMeeting()?->getId();
                })
                ->forMember('userCode', function (Rating $source): string {
                    return $source->getUser()?->getCode();
                });
            $mapper = new AutoMapper($config);
            try {
                $ratingDto = $mapper->map($rating, RatingOutputDto::class);
            } catch (UnregisteredMappingException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return $ratingDto ?? null;
    }
}
