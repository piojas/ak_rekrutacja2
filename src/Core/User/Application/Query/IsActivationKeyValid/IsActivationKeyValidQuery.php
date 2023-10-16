<?php

namespace App\Core\User\Application\Query\IsActivationKeyValid;

use App\Core\User\Domain\User;

class IsActivationKeyValidQuery
{
    public function __construct(public readonly User $user)
    {
    }
}
