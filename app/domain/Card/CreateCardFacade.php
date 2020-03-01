<?php
declare(strict_types=1);

namespace App\Domain\Card;

use App\Model\Database\Entity\Card;
use App\Model\Database\Entity\File;
use App\Model\Database\EntityManager;
use App\Model\File\DirectoryManager;

class CreateCardFacade
{

    private EntityManager $em;

    private DirectoryManager $dm;

    public function __construct(EntityManager $em, DirectoryManager $dm)
    {
        $this->em = $em;
        $this->dm = $dm;
    }

    public function create(File $file): Card
    {
        $card = new Card(
            $file,
            'Image title',
            'Some quick example text to build on the card title and make up the bulk of the card\'s content.'
        );

        $this->em->persist($card);
        $this->em->flush();

        return $card;
    }

}