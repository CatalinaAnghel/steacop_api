<?php
declare(strict_types=1);

namespace App\MessageHandler\Event\MilestoneMeeting;

use App\Entity\MilestoneMeeting;
use App\Message\Event\MilestoneMeeting\MilestoneMeetingCreatedEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;
use Twig\Environment;

#[AsMessageHandler]
class CreateMilestoneMeetingHandler
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
    public function __invoke(MilestoneMeetingCreatedEvent $event): void
    {
        /**
         * @var MilestoneMeeting $meeting
         */
        $meeting = $event->getMeeting();
        $mailContent = $this->twig->render('email/meeting/meeting-created-email.html.twig', [
            'meeting' => [
                'description' => $meeting->getDescription(),
                'scheduledAt' => $meeting->getScheduledAt()?->format('d-m-Y H:i'),
                'projectTitle' => $meeting->getProject()?->getTitle(),
                'link' => $meeting->getLink(),
                'type' => 'Milestone meeting'
            ]
        ]);

        foreach ($event->getReceivers() as $receiver) {
            $email = (new Email())
                ->from($this->senderEmail)
                ->to($receiver)
                ->subject('Thesico - A milestone meeting has been scheduled')
                ->html($mailContent);

            $this->mailer->send($email);
        }
    }
}