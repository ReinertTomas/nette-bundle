<?php
declare(strict_types=1);

namespace App\Modules\Base;

use App\Model\App;
use App\Model\Database\Entity\User;
use Nette\Application\AbortException;
use Nette\Application\UI\ComponentReflection;
use Nette\Security\IUserStorage;

abstract class SecuredPresenter extends BasePresenter
{

    protected User $userLoggedIn;

    /**
     * @param ComponentReflection|mixed $element
     * @throws AbortException
     */
    public function checkRequirements($element): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            if ($this->getUser()->getLogoutReason() === IUserStorage::INACTIVITY) {
                $this->flashInfo('You have been logged out for inactivity');
            }

            $this->redirect(
                App::DESTINATION_SIGN_IN,
                ['backlink' => $this->storeRequest()]
            );
        }
    }

    protected function startup(): void
    {
        parent::startup();

        $this->userLoggedIn = $this->em->getUserRepository()->find($this->user->getId());
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();

        $this->template->userLoggedIn = $this->userLoggedIn;
    }

}