<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
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

    public function changePassword(User $user, string $passwordOld, string $passwordNew): User
    {
        // TODO

        return $user;
    }

}