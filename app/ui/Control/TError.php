<?php
declare(strict_types=1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;
use Nette\Http\IResponse;

/**
 * @mixin BasePresenter
 */
trait TError
{

    public function errorNotFoundEntity(int $id): void
    {
        $this->error(sprintf('_message.entity.notfound "%d"', $id), IResponse::S404_NOT_FOUND);
    }

}