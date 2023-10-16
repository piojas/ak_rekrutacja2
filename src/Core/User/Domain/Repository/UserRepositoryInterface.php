<?php

namespace App\Core\User\Domain\Repository;

use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\User;

interface UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User;

    public function save(User $user): void;

    public function findByActivationKey(string $activationKey): ?User;

    public function isActivationKeyValid(User $user): bool;

    public function findUserByActive(bool $isActive): array;
}
