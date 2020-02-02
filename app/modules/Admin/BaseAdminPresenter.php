<?php
declare(strict_types=1);

namespace App\Modules\Admin;

use App\Modules\Base\SecuredPresenter;
use App\UI\Grid\GridFactory;

abstract class BaseAdminPresenter extends SecuredPresenter
{

    /** @inject */
    public GridFactory $gridFactory;

}