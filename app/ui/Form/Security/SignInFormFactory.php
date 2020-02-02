<?php
declare(strict_types=1);

namespace App\UI\Form\Security;

use App\UI\Form\FormFactory;
use Nette\Application\UI\Form;

class SignInFormFactory
{

    private FormFactory $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(): Form
    {
        $form = $this->formFactory->create();

        $form->addEmail('email')
            ->setRequired();
        $form->addPassword('password');
        $form->addCheckbox('remember', 'Remember me')
            ->setDefaultValue(true);
        $form->addSubmit('submit', 'Sign in');

        return $form;
    }
}