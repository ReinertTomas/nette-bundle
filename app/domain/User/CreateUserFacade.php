<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Security\Passwords;

class CreateUserFacade
{

    private EntityManager $em;

    private Passwords $passwords;

    public function __construct(EntityManager $em, Passwords $passwords)
    {
        $this->em = $em;
        $this->passwords = $passwords;
    }

    /**
     * @param array<string> $data
     * @return User
     */
    public function create(array $data): User
    {
        $user = new User(
            $data['name'],
            $data['surname'],
            $data['email'],
            $this->passwords->hash($data['password'])
        );
        $user->setRole($data['role']);
        $user->activate();

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

}