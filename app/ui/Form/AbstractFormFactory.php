<?php
declare(strict_types=1);

namespace App\UI\Form;

use Nette\Application\UI\Form;

abstract class AbstractFormFactory
{

    public function create(): Form
    {
        return new Form();
    }

}