<?php
declare(strict_types=1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;

/**
 * @mixin BasePresenter
 */
trait TModal
{

    public function redrawModalContent(): void
    {
        $this->redrawControl('modal-content');
    }

    public function handleModal(string $modal, ?array $attr): void
    {
        if ($this->isAjax()) {
            $this->template->add('modal', $modal);
            $this->template->add('modalAttr', $attr);
            $this->redrawControl('modal');
        } else {
            $this->redirect('this');
        }
    }

}