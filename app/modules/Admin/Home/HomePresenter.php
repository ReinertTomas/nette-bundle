<?php
declare(strict_types=1);

namespace App\Modules\Admin\Home;

use App\Modules\Admin\BaseAdminPresenter;
use Ublaboo\DataGrid\DataGrid;

class HomePresenter extends BaseAdminPresenter
{
    private array $data;

    /**
     * HomePresenter constructor.
     */
    public function __construct()
    {
        $this->data = [
            1 => ['id' => 1, 'name' => 'Tomas'],
            2 => ['id' => 2, 'name' => 'David'],
            3 => ['id' => 3, 'name' => 'Petr'],
            4 => ['id' => 4, 'name' => 'Lukas'],
            5 => ['id' => 5, 'name' => 'Iveta']
        ];
    }


    protected function createComponentSimpleGrid(string $name): DataGrid
    {
        $grid = new DataGrid($this, $name);

        $grid->setDataSource($this->data);
        $grid->addColumnText('id', 'Id');
        $grid->addColumnText('name', 'Name');

        return $grid;
    }
}