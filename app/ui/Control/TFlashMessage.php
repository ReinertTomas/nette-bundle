<?php
declare(strict_types=1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;
use Nette\Application\AbortException;
use stdClass;

/**
 * @mixin BasePresenter
 */
trait TFlashMessage
{

    /**
     * @throws AbortException
     */
    public function redrawFlashes(): void
    {
        $this->redrawControl('flashes');
    }

    /**
     * @param string $message
     * @param string $type
     * @param string|null $icon
     * @return stdClass
     */
    public function flashMessageIcon($message, ?string $type, ?string $icon): stdClass
    {
        $flash = parent::flashMessage($message, $type);
        $flash->icon = $icon;
        return $flash;
    }

    public function flashInfo(string $message): stdClass
    {
        return $this->flashMessageIcon($message, 'info', 'info');
    }

    public function flashSuccess(string $message): stdClass
    {
        return $this->flashMessageIcon($message, 'success', 'check');
    }

    public function flashWarning(string $message): stdClass
    {
        return $this->flashMessageIcon($message, 'warning', 'exclamation');
    }

    public function flashError(string $message): stdClass
    {
        return $this->flashMessageIcon($message, 'danger', 'exclamation-triangle');
    }

}