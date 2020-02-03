<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Security\Passwords;

class UpdateUserFacade
{

    private EntityManager $em;

    private Passwords $passwords;

    public function __construct(EntityManager $em, Passwords $passwords)
    {
        $this->em = $em;
        $this->passwords = $passwords;
    }

    public function update(User $user, array $data): User
    {
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);

        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }

        $this->em->flush();

        return $user;
    }

    public function changePassword(User $user, string $oldPassword, string $newPassword): User
    {
        if (!$this->passwords->verify($oldPassword, $user->getPassword())) {
            throw new InvalidArgumentException('User current password is wrong.');
        }
        if ($oldPassword === $newPassword) {
            throw new InvalidArgumentException('Old and new password cannot be same.');
        }

        $user->setPassword($this->passwords->hash($newPassword));
        $this->em->flush();

        return $user;
    }

}