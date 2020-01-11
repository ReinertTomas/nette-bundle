<?php
declare(strict_types=1);

namespace App\Modules\Admin;

use App\Modules\Base\UnsecuredPresenter;
use App\UI\Control\TGridFactory;

abstract class BaseAdminPresenter extends UnsecuredPresenter
{
    use TGridFactory;
}