<?php

namespace App\Core\User\Application\Query\GetUserByActivationKey;

class GetUserByActivationKeyQuery
{
    public function __construct(public readonly string $activationKey)
    {
    }
}
