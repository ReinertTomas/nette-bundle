<?php
declare(strict_types=1);

namespace App\Modules\Base;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\UI\ComponentReflection;
use Nette\InvalidStateException;

abstract class BaseError4xxPresenter extends UnsecuredPresenter
{
    /**
     * @throws BadRequestException
     */
    protected function startup(): void
    {
        parent::startup();

        if ($this->getRequest() !== null AND $this->getRequest()->isMethod(Request::FORWARD)) {
            return;
        }

        $this->error();
    }

    public function renderDefault(BadRequestException $exception): void
    {
        $rf1 = new ComponentReflection(static::class);
        $fileName = $rf1->getFileName();

        // Validate if class is not in PHP core
        if ($fileName === false) {
            throw new InvalidStateException('Class is defined in the PHP core or in a PHP extension');
        }

        $dir = dirname($fileName);

        // Load template 403.latte or 404.latte or ... 4xx.latte
        $file = $dir . '/templates/' . $exception->getCode() . '.latte';
        $this->template->setFile(is_file($file) ? $file : $dir . '/templates/4xx.latte');
    }
}