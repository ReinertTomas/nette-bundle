<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Security\Identity;
use App\Model\Utils\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractEntity
{

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    public const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_USER => 'User'
    ];

    public const STATE_FRESH = 1;
    public const STATE_ACTIVATED = 2;
    public const STATE_BLOCKED = 3;

    public const STATES = [
        self::STATE_FRESH => 'FRESH',
        self::STATE_ACTIVATED => 'ACTIVATED',
        self::STATE_BLOCKED => 'BLOCKED'
    ];

    use TCreatedAt;
    use TUpdatedAt;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected string $surname;

    /**
     * @ORM\Column(type="string", length=255, unique=TRUE)
     */
    protected string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $password;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected string $role;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $state;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected ?DateTime $lastLoggedAt;

    public function __construct(string $name, string $surname, string $email, string $password)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;

        $this->role = self::ROLE_USER;
        $this->state = self::STATE_FRESH;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getFullname(): string
    {
        return "{$this->name} {$this->surname}";
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        if (!array_key_exists($role, self::ROLES)) {
            throw new InvalidArgumentException(sprintf('Unsupported role %s', $role));
        }
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state): void
    {
        if (!array_key_exists($state, self::STATES)) {
            throw new InvalidArgumentException(sprintf('Unsupported state %s', $state));
        }
        $this->state = $state;
    }

    public function block(): void
    {
        $this->state = self::STATE_BLOCKED;
    }

    public function activate(): void
    {
        $this->state = self::STATE_ACTIVATED;
    }

    public function isActivated(): bool
    {
        return $this->state === self::STATE_ACTIVATED;
    }

    public function getLastLoggedAt(): ?DateTime
    {
        return $this->lastLoggedAt;
    }

    public function changeLoggedAt(): void
    {
        $this->lastLoggedAt = new DateTime();
    }

    public function toIdentity(): Identity
    {
        return new Identity($this->getId(), [$this->role], [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'state' => $this->state,
            'lastLoggedAt' => $this->lastLoggedAt
        ]);
    }

}