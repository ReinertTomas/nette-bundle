<?php
declare(strict_types=1);

namespace App\Modules\Admin\Home;

use App\Modules\Admin\BaseAdminPresenter;

class HomePresenter extends BaseAdminPresenter
{

    public function renderDefault(): void
    {
        $this->flashInfo("Example flash info.");
        $this->flashSuccess("Example flash success.");
        $this->flashWarning("Example flash warning.");
        $this->flashError("Example flash error.");
        $this->flashMessage("Example flash origin.");
    }

}