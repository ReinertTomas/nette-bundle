<?php
declare(strict_types=1);

namespace App\Modules\Admin\Example;

use App\Modules\Admin\BaseAdminPresenter;
use Ublaboo\DataGrid\DataGrid;

class ExamplePresenter extends BaseAdminPresenter
{
    private array $data;

    public function actionDatagrid()
    {
        $this->data = json_decode(file_get_contents("./table.json"), true);
    }

    protected function createComponentSimpleGrid(string $name): DataGrid
    {
        $grid = $this->gridFactory->create($this, $name);

        $grid->setDataSource($this->data);
        $grid->addColumnText('id', 'Id');
        $grid->addColumnText('name', 'Name');
        $grid->addColumnText('position', 'Position');
        $grid->addColumnText('salary', 'Salary');
        $grid->addColumnText('date', 'Date');
        $grid->addColumnText('office', 'Office');
        $grid->addColumnText('number', 'Number');

        return $grid;
    }
}