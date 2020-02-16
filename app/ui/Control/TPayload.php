<?php
declare(strict_types=1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;

/**
 * @mixin BasePresenter
 */
trait TPayload
{

    public function setAjaxPostGet(): void
    {
        $this->payload->postGet = true;
        $this->payload->url = $this->link('this');
    }

}