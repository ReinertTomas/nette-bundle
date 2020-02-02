<?php
declare(strict_types=1);

namespace App\Modules\Admin\User;

use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Grid\User\UserGridFactory;
use Ublaboo\DataGrid\DataGrid;

class UserPresenter extends BaseAdminPresenter
{

    /** @inject */
    public UserGridFactory $userGridFactory;

    protected function createComponentUsersGrid(string $name): DataGrid
    {
        return $this->userGridFactory->create($this, $name);
    }

}