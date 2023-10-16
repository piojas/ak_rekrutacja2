<?php

namespace App\Core\User\Domain\Activation;

interface ActivationLinkGenerator
{
    public function generateActivationLink(string $activationKey): string;
}