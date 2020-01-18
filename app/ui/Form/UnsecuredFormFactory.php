<?php
declare(strict_types=1);

namespace App\UI\Form;

use Nette\Application\UI\Form;

final class UnsecuredFormFactory extends AbstractFormFactory
{

    public function create(): Form
    {
        return parent::create();
    }

}