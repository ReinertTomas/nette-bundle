<?php
declare(strict_types=1);

namespace App\UI\Grid\User;

use App\Model\Database\EntityManager;
use App\UI\Grid\GridFactory;
use Nette\ComponentModel\IContainer;
use Ublaboo\DataGrid\DataGrid;

final class UserGridFactory
{

    private GridFactory $gridFactory;

    private EntityManager $em;

    public function __construct(GridFactory $gridFactory, EntityManager $em)
    {
        $this->gridFactory = $gridFactory;
        $this->em = $em;
    }


    public function create(IContainer $parent, string $name): DataGrid
    {
        $grid = $this->gridFactory->create($parent, $name);
        $grid->setDataSource($this->em->getUserRepository()->createQueryBuilder('u1'));

        $grid->addColumnText('name', 'Name');
        $grid->addColumnText('surname', 'Surname');
        $grid->addColumnText('email', 'Email');
        $grid->addColumnText('role', 'Role');
        $grid->addColumnText('state', 'State');
        $grid->addColumnText('createdAt', 'Created');
        $grid->addColumnText('updatedAt', 'Updated');
        $grid->addColumnText('lastLoggedAt', 'Last Logged');

        $grid->addAction('edit', '', ':Admin:User:edit')
            ->setIcon('pencil-alt');

        return $grid;
    }

}