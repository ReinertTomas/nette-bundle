<?php
declare(strict_types=1);

namespace App\Modules\Front\Error4xx;

use ReflectionClass;
use App\Modules\Base\BaseError4xxPresenter;

final class Error4xxPresenter extends BaseError4xxPresenter
{
    public function formatLayoutTemplateFiles(): array
    {
        $list = parent::formatLayoutTemplateFiles();

        $rf = new ReflectionClass(static::class);
        $dir = dirname($rf->getFileName());

        $layout = "$dir/../templates/@layout.latte";

        if (is_file($layout)) {
            array_unshift($list, $layout);
        }

        return  $list;
    }
}