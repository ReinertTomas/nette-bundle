<?php
declare(strict_types=1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Card;

/**
 * @method Card|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Card|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Card[] findAll()
 * @method Card[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Card>
 */
class CardRepository extends AbstractRepository
{

}