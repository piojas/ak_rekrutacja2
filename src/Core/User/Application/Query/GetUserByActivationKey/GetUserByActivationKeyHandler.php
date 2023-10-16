<?php

namespace App\Core\User\Application\Query\GetUserByActivationKey;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\User;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUserByActivationKeyHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetUserByActivationKeyQuery $query): array
    {
        $users = $this->userRepository->findByActivationKey($query->activationKey);

        return array_map(function (User $user) {
            return new UserDTO(
                $user->getEmail()
            );
        }, $users ?? []);
    }
}
