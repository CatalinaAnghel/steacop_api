<?php
declare(strict_types=1);

namespace App\MessageHandler\Event\Functionality;

use App\Message\Event\Functionality\FunctionalityCreatedEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsMessageHandler]
class CreateFunctionalityHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly string $senderEmail
    ) {
        date_default_timezone_set('UTC');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(FunctionalityCreatedEvent $event): void
    {
        $mailContent = $this->twig->render('email/functionality/functionality-created-email.html.twig', [
            'project' => [
                'title' => $event->getFunctionality()->getTitle(),
                'dueDate' => $event->getFunctionality()->getDueDate()?->format('d-m-Y H:i'),
                'projectTitle' => $event->getFunctionality()->getProject()?->getTitle(),
                'type' => $event->getFunctionality()->getType()?->getName(),
                'description' => $event->getFunctionality()->getDescription()
            ]

        ]);

        foreach ($event->getReceivers() as $receiver) {
            $email = (new Email())
                ->from($this->senderEmail)
                ->to($receiver)
                ->subject('Steacop - A new functionality has been created')
                ->html($mailContent);

            $this->mailer->send($email);
        }
    }
}