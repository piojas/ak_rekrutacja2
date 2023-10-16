<?php

namespace App\Core\User\Application\Service;

use App\Core\User\Domain\Activation\ActivationLinkGenerator;

class EmailService
{    
    private string $subject = 'Aktywacja konta';
    private string $message = 'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h. Aktywuj konto klikając w poniższy link: %s';

    public function __construct(
        private readonly ActivationLinkGenerator $activationLinkGenerator
    ) {
        $this->activationLinkGenerator = $activationLinkGenerator;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getActivationMessage(string $activationKey): int
    {
        return sprintf(
            $this->message, 
            $this->activationLinkGenerator
                 ->generateActivationLink($activationKey)
        );
    }
}