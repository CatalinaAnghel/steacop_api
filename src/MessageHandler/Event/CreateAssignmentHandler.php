<?php
declare(strict_types=1);

namespace App\MessageHandler\Event;

use App\Message\Event\AssignmentCreatedEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsMessageHandler]
class CreateAssignmentHandler
{
    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig) {
        date_default_timezone_set('UTC');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(AssignmentCreatedEvent $event): void
    {
        $mailContent = $this->twig->render('email/assignment-created-email.html.twig', [
            'assignment' => [
                'title' => $event->getAssignment()->getTitle(),
                'dueDate' =>  $event->getAssignment()->getDueDate()?->format('d-m-Y H:i'),
                'description' => $event->getAssignment()->getDescription()
            ]

        ]);
        $email = (new Email())
            ->from('info@steacop.com')
            ->to($event->getReceiver())
            ->subject('Steacop - new assignment added')
            ->html($mailContent);

        $this->mailer->send($email);
    }
}
