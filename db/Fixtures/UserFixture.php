<?php
declare(strict_types=1);

namespace Database\Fixtures;

use App\Model\Database\Entity\User;
use App\Model\Security\Passwords;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{

    public function getOrder(): int
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUsers() as $user) {
            $entity = new User(
                $user['name'],
                $user['surname'],
                $user['email'],
                $this->container->getByType(Passwords::class)->hash($user['password'])
            );
            $entity->setRole($user['role']);
            $entity->activate();

            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getUsers(): iterable
    {
        yield [
            'name' => 'Admin',
            'surname' => 'Adminer',
            'email' => 'admin@admin.net',
            'password' => 'Admin123!',
            'role' => User::ROLE_ADMIN
        ];
        yield [
            'name' => 'Test',
            'surname' => 'Tester',
            'email' => 'test@test.net',
            'password' => 'Test123!',
            'role' => User::ROLE_USER
        ];
    }

}