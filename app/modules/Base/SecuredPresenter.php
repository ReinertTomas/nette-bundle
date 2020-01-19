<?php
declare(strict_types=1);

namespace App\Modules\Base;

use App\Model\App;
use Nette\Application\AbortException;
use Nette\Application\UI\ComponentReflection;
use Nette\Security\IUserStorage;

abstract class SecuredPresenter extends BasePresenter
{

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

}