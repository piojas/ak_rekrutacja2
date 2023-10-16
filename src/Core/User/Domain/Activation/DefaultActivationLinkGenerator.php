<?php

namespace App\Core\User\Domain\Activation;

use Symfony\Component\HttpFoundation\RequestStack;

class DefaultActivationLinkGenerator implements ActivationLinkGenerator
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function generateActivationLink(string $activationKey): string
    {
        return sprintf(
            '%s/activate?token=%s',
            $this->getCurrentUrl(),
            $activationKey
        );
    }

    private function getCurrentUrl(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? $request->getUri() : '';
    }
}