<?php
declare(strict_types=1);

namespace App\State\Processor\Rating;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Rating\Input\CreateRatingInputDto;
use App\Dto\Rating\Output\RatingOutputDto;
use App\Entity\GuidanceMeeting;
use App\Entity\Rating;
use App\Entity\User;
use App\Validator\Contracts\ValidatorInterface;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class CreateRatingStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly LoggerInterface        $logger,
                                private readonly Security               $security,
                                private readonly ValidatorInterface     $ratingValidator
    ) {}

    /**
     * @inheritDoc
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):
    ?RatingOutputDto
    {
        $guidanceMeetingRepo = $this->entityManager->getRepository(GuidanceMeeting::class);
        $guidanceMeeting = $guidanceMeetingRepo->findOneBy(['id' => $data->getGuidanceMeetingId()]);
        if (null !== $guidanceMeeting) {
            $this->ratingValidator->validate($data, $guidanceMeeting);
            try {
                $config = new AutoMapperConfig();
                $config->registerMapping(CreateRatingInputDto::class,
                    Rating::class);
                $mapper = new AutoMapper($config);

                $userRepo = $this->entityManager->getRepository(User::class);
                $user = $userRepo->findOneBy(['email' => $this->security->getUser()?->getUserIdentifier()]);

                /**
                 * @var Rating $meeting
                 */
                $rating = $mapper->map($data, Rating::class);
                $rating->setMeeting($guidanceMeeting);
                $rating->setUser($user);
                $rating->setValue($data->getValue());
                $this->entityManager->persist($rating);
                $this->entityManager->flush();

                $configOutput = new AutoMapperConfig();
                $configOutput->registerMapping(Rating::class, RatingOutputDto::class);
                $mapper = new AutoMapper($configOutput);

                /**
                 * @var RatingOutputDto $ratingDto
                 */
                $ratingDto = $mapper->map($rating, RatingOutputDto::class);
                $ratingDto->setGuidanceMeetingId($guidanceMeeting->getId());
                $ratingDto->setUserCode($user?->getCode());
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        } else {
            throw new NotFoundHttpException('The guidance meeting could not be found');
        }

        return $ratingDto ?? null;
    }
}
