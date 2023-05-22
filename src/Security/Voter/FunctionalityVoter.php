<?php

namespace App\Security\Voter;

use App\ApiResource\FunctionalitiesOrdering;
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
    public const ORDER = 'FUNCTIONALITY_ORDER';

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
            self::ORDER  => $subject instanceof FunctionalitiesOrdering,
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

        switch ($attribute) {
            case self::CREATE:
                $projectRepo = $this->entityManager->getRepository(Project::class);
                /**
                 * @var CreateFunctionalityInputDto $subject
                 */
                $project = $projectRepo->findOneBy(['id' => $subject->getProjectId()]);

                $authorized = null !== $project && (
                        $project->getStudent()?->getUser()?->getEmail() === $user->getUserIdentifier() ||
                        $project->getSupervisor()?->getUser()?->getEmail() === $user->getUserIdentifier()
                    );
                break;
            case self::ORDER:
                /**
                 * @var FunctionalitiesOrdering $subject
                 */
                $finished = false;

                foreach ($subject->getOrderingCollections() as $orderingCollection) {
                    if (isset($orderingCollection->getFunctionalities()[0])) {
                        $id = $orderingCollection->getFunctionalities()[0];
                        break;
                    }
                }

                if (isset($id)) {
                    $repo = $this->entityManager->getRepository(Functionality::class);
                    $functionality = $repo->findOneBy(['id' => $id]);

                    if (null === $functionality) {
                        throw new NotFoundHttpException();
                    }
                    $authorized = $functionality->getProject()?->getSupervisor()?->getUser()?->getEmail() ===
                        $user->getUserIdentifier() ||
                        $functionality->getProject()?->getStudent()?->getUser()?->getEmail() ===
                        $user->getUserIdentifier();
                } else {
                    $authorized = false;
                }
                break;
            default:
                $id = (int)$this->requestBody->getCurrentRequest()->attributes->get('id');
                $repo = $this->entityManager->getRepository(Functionality::class);
                $functionality = $repo->findOneBy(['id' => $id]);

                if (null === $functionality) {
                    throw new NotFoundHttpException();
                }
                $authorized = $functionality->getProject()?->getSupervisor()?->getUser()?->getEmail() ===
                    $user->getUserIdentifier() ||
                    $functionality->getProject()?->getStudent()?->getUser()?->getEmail() === $user->getUserIdentifier();
        }
        return $authorized;
    }
}
