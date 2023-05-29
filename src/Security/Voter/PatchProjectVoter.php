<?php
declare(strict_types=1);

namespace App\Security\Voter;

use App\Dto\Project\Input\PatchProjectInputDto;
use App\Entity\Project;
use App\Helper\RolesHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PatchProjectVoter extends Voter
{
    public const PATCH = 'PROJECT_EDIT';

    public function __construct(
        private readonly RequestStack           $requestBody,
        private readonly EntityManagerInterface $entityManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof PatchProjectInputDto && $attribute === self::PATCH;
    }

    /**
     * @param string $attribute
     * @param PatchProjectInputDto $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $projectId = (int)$this->requestBody->getCurrentRequest()->attributes->get('id');
        $repo = $this->entityManager->getRepository(Project::class);
        $project = $repo->findOneBy(['id' => $projectId]);

        if (null === $project) {
            throw new NotFoundHttpException();
        }

        if (in_array(RolesHelper::RoleTeacher->value, $user->getRoles(), true) &&
            $project->getSupervisor()?->getUser()?->getEmail() === $user->getUserIdentifier()) {
            return true;
        }

        $student = $project->getStudent();
        return in_array(RolesHelper::RoleStudent->value, $user->getRoles(), true) &&
            $student?->getUser()?->getEmail() === $user->getUserIdentifier();
    }
}