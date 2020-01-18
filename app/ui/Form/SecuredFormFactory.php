<?php
declare(strict_types=1);

namespace App\UI\Form;

use Nette\Application\UI\Form;

final class SecuredFormFactory extends AbstractFormFactory
{

    public function create(): Form
    {
        $form = parent::create();
        $form->addProtection();
        return $form;
    }

}