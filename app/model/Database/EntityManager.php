<?php
declare(strict_types=1);

namespace App\Model\Database;

use App\Model\Database\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Nettrine\ORM\EntityManagerDecorator;

final class EntityManager extends EntityManagerDecorator
{

    use TRepositories;

    /**
     * @param string $entityName
     * @return AbstractRepository<T>|ObjectRepository<T>
     * @internal
     * @phpstan-template T
     * @phpstan-param class-string<T> $entityName
     */
    public function getRepository($entityName): ObjectRepository
    {
        return parent::getRepository($entityName);
    }

}