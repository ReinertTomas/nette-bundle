<?php
declare(strict_types=1);

namespace App\Model\Security\Authenticator;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Security\Passwords;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;

final class UserAuthenticator implements IAuthenticator
{

    private EntityManager $em;

    private Passwords $passwords;

    public function __construct(EntityManager $em, Passwords $passwords)
    {
        $this->em = $em;
        $this->passwords = $passwords;
    }

    /**
     * @param array<string> $credentials
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials): IIdentity
    {
        [$email, $password] = $credentials;

        $user = $this->em->getUserRepository()->findOneByEmail($email);
        if (!$user) {
            throw new AuthenticationException('The email is incorrect.', self::IDENTITY_NOT_FOUND);
        } elseif (!$user->isActivated()) {
            throw new AuthenticationException('The user is not active.', self::INVALID_CREDENTIAL);
        } elseif (!$this->passwords->verify($password, $user->getPassword())) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        }

        return $this->createIdentity($user);
    }

    private function createIdentity(User $user): IIdentity
    {
        $user->changeLoggedAt();
        $this->em->flush();

        return $user->toIdentity();
    }

}