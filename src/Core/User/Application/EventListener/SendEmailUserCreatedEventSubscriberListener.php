<?php

namespace App\Core\User\Application\EventListener;

use App\Core\User\Domain\Event\UserCreatedEvent;
use App\Core\User\Application\Service\EmailService;
use App\Core\User\Domain\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendEmailUserCreatedEventSubscriberListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly NotificationInterface $mailer,
        private readonly EmailService $emailService
    ){}

    public function send(UserCreatedEvent $event): void
    {
        $this->mailer->sendEmail(
            $event->getUserEmail(),
            $this->emailService->getSubject(),
            $this->emailService->getActivationMessage($event->getUserActivationKey())
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'send'
        ];
    }
}
