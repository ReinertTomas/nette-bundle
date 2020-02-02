<?php
declare(strict_types=1);

namespace App\UI\Form\User;

use App\Model\Database\Entity\User;
use App\Model\Security\SecurityUser;
use App\UI\Form\FormFactory;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SelectBox;

class EditUserFormFactory
{

    private FormFactory $formFactory;

    private SecurityUser $securityUser;

    public function __construct(FormFactory $formFactory, SecurityUser $securityUser)
    {
        $this->formFactory = $formFactory;
        $this->securityUser = $securityUser;
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
            ->setRequired();
        $form->addSubmit('submit', 'Save');

        return $form;
    }

    public function setDefaults(Form $form, User $user): void
    {
        /** @var SelectBox $roleSelect */
        $roleSelect = $form->getComponent('role');
        $roleSelect->setItems(User::ROLES);

        if (!$this->securityUser->isAdmin()) {
            $roleSelect->setDisabled();
        }

        $form->setDefaults([
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ]);
    }

}