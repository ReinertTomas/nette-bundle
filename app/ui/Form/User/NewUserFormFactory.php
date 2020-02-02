<?php
declare(strict_types=1);

namespace App\UI\Form\User;

use App\Model\Database\Entity\User;
use App\Model\Security\Passwords;
use App\UI\Form\FormFactory;
use Nette\Application\UI\Form;

class NewUserFormFactory
{

    private FormFactory $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(): Form
    {
        $form = $this->formFactory->createSecured();

        $form->addText('name', 'Name')
            ->setRequired();
        $form->addText('surname', 'Surname')
            ->setRequired();
        $form->addText('email', 'Email')
            ->setRequired();
        $form->addSelect('role', 'Role')
            ->setRequired()
            ->setItems(User::ROLES)
            ->setDefaultValue(User::ROLE_USER);
        $form->addPassword('password', 'Password')
            ->setRequired()
            ->addRule(Form::MIN_LENGTH, 'Password length must be min. 8 chars', 8)
            ->addRule(Form::PATTERN, 'Password is not strong', Passwords::PATTERN);
        $form->addPassword('passwordConfirm', 'Password Confirm')
            ->setRequired()
            ->setOmitted()
            ->addRule(Form::EQUAL, 'Passwords are different.', $form['password']);
        $form->addSubmit('submit', 'Create User');

        return $form;
    }

}