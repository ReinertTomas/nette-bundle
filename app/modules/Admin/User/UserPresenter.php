<?php
declare(strict_types=1);

namespace App\Modules\Admin\User;

use App\Domain\User\CreateUserFacade;
use App\Domain\User\UpdateUserFacade;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Form\Security\PasswordFormFactory;
use App\UI\Form\User\EditUserFormFactory;
use App\UI\Form\User\NewUserFormFactory;
use App\UI\Grid\User\UserGridFactory;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;

class UserPresenter extends BaseAdminPresenter
{

    private int $id;

    /** @inject */
    public UserGridFactory $userGridFactory;

    /** @inject */
    public NewUserFormFactory $newUserFormFactory;

    /** @inject */
    public EditUserFormFactory $editUserFormFactory;

    /** @inject */
    public PasswordFormFactory $passwordFormFactory;

    /** @inject */
    public CreateUserFacade $createUserFacade;

    /** @inject */
    public UpdateUserFacade $updateUserFacade;

    public function actionEdit(int $id): void
    {
        $this->id = $id;

        $user = $this->em->getUserRepository()->find($id);
        if (!$user) {
            $this->error(sprintf('User id "%d" not found.', $id));
        }

        /** @var Form $form */
        $form = $this->getComponent('editUserForm');
        $this->editUserFormFactory->setDefaults($form, $user);
    }

    public function renderEdit(): void
    {
        $this->template->isOwnProfile = $this->user->getId() === $this->id;
    }

    protected function createComponentUsersGrid(string $name): DataGrid
    {
        return $this->userGridFactory->create($this, $name);
    }

    protected function createComponentNewUserForm(): Form
    {
        $form = $this->newUserFormFactory->create();

        $form->onSuccess[] = function (Form $form): void {
            $values = (array) $form->getValues();

            try {
                $user = $this->createUserFacade->create($values);
            } catch (UniqueConstraintViolationException $e) {
                $this->flashError(sprintf('Email "%s" is already used.', $values['email']));
                return;
            }

            $this->flashSuccess(sprintf('User "%s" created.', $user->getEmail()));
            $this->redirect(':Admin:User:');
        };

        return $form;
    }

    protected function createComponentEditUserForm(): Form
    {
        $form = $this->editUserFormFactory->create();

        $form->onSuccess[] = function (Form $form): void {
            $values = (array) $form->getValues();

            $user = $this->em->getUserRepository()->find($this->id);

            try {
                $this->updateUserFacade->update($user, $values);
            } catch (UniqueConstraintViolationException $e) {
                $this->flashError(sprintf('Email "%s" is already used.', $user->getEmail()));
                return;
            }

            $this->flashSuccess(sprintf('User "%s" updated.', $user->getEmail()));
            $this->redirect('this');
        };

        return $form;
    }

    protected function createComponentChangePasswordForm(): Form
    {
        $form = $this->passwordFormFactory->create();

        $form->onSuccess[] = function (Form $form): void {
            $values = (array) $form->getValues();

            $user = $this->em->getUserRepository()->find($this->user->getId());

            try {
                $this->updateUserFacade->changePassword($user, $values['passwordOld'], $values['passwordNew']);
            } catch (InvalidArgumentException $e) {
                $this->flashError($e->getMessage());
                return;
            }

            $this->flashSuccess('User password was changed.');
            $this->redirect('this');
        };

        return $form;
    }

}