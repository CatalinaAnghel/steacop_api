<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener {
    private const HEADER_CTY_JWT = 'JWT';

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager) {
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event):void {
        $payload = $event->getData();
        $repo = $this->entityManager->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $payload['username']]);
        if($user !== null){
            $payload['code'] = $user->getCode();
            $payload['id'] = $user->getId();
        }

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = self::HEADER_CTY_JWT;

        $event->setHeader($header);
    }
}
