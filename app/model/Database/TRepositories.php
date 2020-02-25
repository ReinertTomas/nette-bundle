<?php
declare(strict_types=1);

namespace App\Model\Database;

use App\Model\Database\Entity\File;
use App\Model\Database\Entity\User;
use App\Model\Database\Repository\FileRepository;
use App\Model\Database\Repository\UserRepository;

/**
 * @mixin EntityManager
 */
trait TRepositories
{

    public function getUserRepository(): UserRepository
    {
        return $this->getRepository(User::class);
    }

    public function getFileRepository(): FileRepository
    {
        return $this->getRepository(File::class);
    }

}