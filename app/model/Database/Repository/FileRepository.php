<?php
declare(strict_types=1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\File;

/**
 * @method File|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method File|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method File[] findAll()
 * @method File[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<File>
 */
class FileRepository extends AbstractRepository
{

    /**
     * @return File[]
     */
    public function findImages(): array
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.type = :type')
            ->setParameter('type', File::TYPE_IMAGE);

        return $qb->getQuery()
            ->getResult();
    }

}