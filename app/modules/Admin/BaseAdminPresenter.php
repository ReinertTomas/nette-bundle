<?php
declare(strict_types=1);

namespace App\Modules\Admin;

use App\Modules\Base\UnsecuredPresenter;
use App\UI\Grid\GridFactory;

abstract class BaseAdminPresenter extends UnsecuredPresenter
{

    /** @inject */
    public GridFactory $gridFactory;

}