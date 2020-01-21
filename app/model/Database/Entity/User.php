<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractEntity
{

    use TCreatedAt;
    use TUpdatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE, unique=FALSE)
     */
    protected string $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE, unique=TRUE)
     */
    protected string $email;

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

    public function getFullname(): string
    {
        return "{$this->name} {$this->surname}";
    }

}