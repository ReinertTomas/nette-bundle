<?php
declare(strict_types=1);

namespace App\UI\Form;

use Nette\Application\UI\Form;

class FormFactory
{

    public function create(): Form
    {
        return new Form();
    }

    public function createSecured(): Form
    {
        $form = $this->create();
        $form->addProtection();

        return $form;
    }

}