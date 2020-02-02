<?php
declare(strict_types=1);

namespace App\Modules\Base;

use App\Model\Database\EntityManager;
use App\UI\Control\TFlashMessage;
use App\UI\Control\TModal;
use App\UI\Form\FormFactory;
use Contributte\Application\UI\Presenter\StructuredTemplates;
use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{

    use StructuredTemplates;
    use TFlashMessage;
    use TModal;

    /** @inject */
    public EntityManager $em;

}