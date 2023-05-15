<?php

namespace App\Security\Voter;

use App\Dto\Functionality\Input\CreateFunctionalityInputDto;
use App\Dto\Functionality\Input\PatchFunctionalityInputDto;
use App\Entity\Functionality;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FunctionalityVoter extends Voter
{
    public const EDIT = 'FUNCTIONALITY_EDIT';
    public const CREATE = 'FUNCTIONALITY_CREATE';
    public const DELETE = 'FUNCTIONALITY_DELETE';
    public const VIEW = 'FUNCTIONALITY_VIEW';

    public function __construct(
        private readonly RequestStack           $requestBody,
        private readonly EntityManagerInterface $entityManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match ($attribute) {
            self::CREATE => $subject instanceof CreateFunctionalityInputDto,
            self::EDIT   => $subject instanceof PatchFunctionalityInputDto,
            self::DELETE => true,
            default      => false,
        };
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (self::CREATE === $attribute) {
            $projectRepo = $this->entityManager->getRepository(Project::class);
            /**
             * @var CreateFunctionalityInputDto $subject
             */
            $project = $projectRepo->findOneBy(['id' => $subject->getProjectId()]);

            return null !== $project && (
                    $project->getStudent()?->getUser()?->getEmail() === $user->getUserIdentifier() ||
                    $project->getSupervisor()?->getUser()?->getEmail() === $user->getUserIdentifier()
                );
        }

        $id = (int)$this->requestBody->getCurrentRequest()->attributes->get('id');
        $repo = $this->entityManager->getRepository(Functionality::class);
        $functionality = $repo->findOneBy(['id' => $id]);

        if (null === $functionality) {
            throw new NotFoundHttpException();
        }
        return $functionality->getProject()?->getSupervisor()?->getUser()?->getEmail() === $user->getUserIdentifier() ||
            $functionality->getProject()?->getStudent()?->getUser()?->getEmail() === $user->getUserIdentifier();
    }
}
