<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Project;
use App\Entity\Student;
use App\Entity\Supervisor;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener
{
    private const HEADER_CTY_JWT = 'JWT';

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $repo = $this->entityManager->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $payload['username']]);
        if ($user !== null) {
            $payload['code'] = $user->getCode();
            $payload['id'] = $user->getId();
            $personsRepo = $this->entityManager->getRepository(Student::class);

            $personData = $personsRepo->findOneBy(['user' => $user->getId()]);
            if (null === $personData) {
                $personsRepo = $this->entityManager->getRepository(Supervisor::class);
                $personData = $personsRepo->findOneBy(['user' => $user->getId()]);
                if(null !== $personData){
                    $payload['projectIds'] = [];
                    foreach ($personData->getProjects() as $project){
                        /**
                         * @var Project $project
                         */
                        $payload['projectIds'][] = $project->getId();
                    }
                }
            } else {
                // student
                $payload['projectIds'] = [$personData->getProject()?->getId()];
            }
            $payload['fullName'] = null !== $personData ?
                $personData->getFirstName() . ' ' . $personData->getLastName() :
                'Admin';
        }

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = self::HEADER_CTY_JWT;

        $event->setHeader($header);
    }
}
