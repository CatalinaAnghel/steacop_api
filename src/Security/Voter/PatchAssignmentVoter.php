<?php

namespace App\Security\Voter;

use App\Dto\Assignment\Input\PatchAssignmentInputDto;
use App\Entity\Assignment;
use App\Helper\RolesHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PatchAssignmentVoter extends Voter
{
    public const PATCH = 'PATCH';

    public function __construct(
        private readonly RequestStack           $requestBody,
        private readonly EntityManagerInterface $entityManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof PatchAssignmentInputDto && $attribute === self::PATCH;
    }

    /**
     * @param string $attribute
     * @param PatchAssignmentInputDto $subject
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

        $assignmentId = (int)$this->requestBody->getCurrentRequest()->attributes->get('id');
        $repo = $this->entityManager->getRepository(Assignment::class);
        $assignment = $repo->findOneBy(['id' => $assignmentId]);

        if (null === $assignment) {
            throw new NotFoundHttpException();
        }

        if (in_array(RolesHelper::RoleTeacher->value, $user->getRoles(), true) &&
            $assignment->getProject()?->getSupervisor()?->getUser()?->getEmail() === $user->getUserIdentifier()) {
            return true;
        }

        $student = $assignment->getProject()?->getStudent();
        return in_array(RolesHelper::RoleStudent->value, $user->getRoles(), true) &&
            $student?->getUser()?->getEmail() === $user->getUserIdentifier();
    }
}
