<?php
declare(strict_types=1);

namespace App\Model\Database\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TId
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    public function getId(): int
    {
        return $this->id;
    }

}