<?php
declare(strict_types=1);

namespace App\UI\Grid;

use Nette\ComponentModel\IContainer;
use Ublaboo\DataGrid\DataGrid;

final class GridFactory
{
    public function create(?IContainer $parent = null, ?string $name = null): DataGrid
    {
        $grid = new DataGrid($parent, $name);
        return $grid;
    }
}