<?php
declare(strict_types=1);

namespace App\MessageHandler\Event\Assignment;

use App\Message\Event\Assignment\AssignmentGradedEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsMessageHandler]
class GradeAssignmentHandler
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
    public function __invoke(AssignmentGradedEvent $event): void
    {
        $mailContent = $this->twig->render('email/assignment/assignment-graded-email.html.twig', [
            'assignment' => [
                'title' => $event->getAssignment()->getTitle(),
                'grade' => $event->getAssignment()->getGrade(),
                'supervisorEmail' => $event->getAssignment()?->getProject()
                        ?->getSupervisor()?->getUser()?->getEmail()
            ]

        ]);
        $email = (new Email())
            ->from($this->senderEmail)
            ->to($event->getReceiver())
            ->subject('Steacop - Your assignment has been graded')
            ->html($mailContent);

        $this->mailer->send($email);
    }
}