<?php
declare(strict_types=1);

namespace App\UI\Control;

use App\UI\Grid\GridFactory;

trait TGridFactory
{
    protected GridFactory $gridFactory;

    public function injectDatagridFactory(GridFactory $gridFactory): void
    {
        $this->gridFactory = $gridFactory;
    }
}