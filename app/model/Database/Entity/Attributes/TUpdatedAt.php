<?php
declare(strict_types=1);

namespace App\Model\Database\Entity\Attributes;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TUpdatedAt
{

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected ?DateTime $updatedAt;

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     * @internal
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

}