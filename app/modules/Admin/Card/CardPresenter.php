<?php
declare(strict_types=1);

namespace App\Modules\Admin\Card;

use App\Domain\File\FileFacade;
use App\Domain\Card\CreateCardFacade;
use App\Model\Database\Entity\Card;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Control\Dropzone\DropzoneControl;
use App\UI\Control\Dropzone\DropzoneFactory;
use App\UI\Form\Card\CardFormFactory;
use App\UI\Form\Card\CardUploadFormFactory;
use Nette\Application\UI\Form;

class CardPresenter extends BaseAdminPresenter
{

    public int $id;

    private Card $card;

    /** @inject */
    public CardFormFactory $cardFormFactory;

    /** @inject */
    public CardUploadFormFactory $uploadFormFactory;

    /** @inject */
    public DropzoneFactory $dropzoneFactory;

    /** @inject */
    public FileFacade $fileFacade;

    /** @inject */
    public CreateCardFacade $createGalleryFacade;

    public function actionDefault(): void
    {
        /** @var Form $form */
        $form = $this->getComponent('uploadForm');
        /** @var DropzoneControl $dropzone */
        $dropzone = $this->getComponent('dropzone');
        $dropzone->setForm($form);
    }

    public function actionEdit(int $id)
    {
        $this->id = $id;

        $this->card = $this->em->getCardRepository()->find($this->id);
        if (!$this->card) {
            $this->errorNotFoundEntity($this->id);
        }

        /** @var Form $form */
        $form = $this->getComponent('cardForm');
        $this->cardFormFactory->setDefaults($form, $this->card);
    }

    public function renderDefault(): void
    {
        $this->template->cards = $this->em->getCardRepository()->findAll();
    }

    public function renderEdit(int $id): void
    {
        $this->template->card = $this->card;
    }

    public function handleDelete(int $id)
    {
        $card = $this->em->getCardRepository()->find($id);
        if (!$card) {
            $this->errorNotFoundEntity($id);
        }

        $this->em->remove($card);
        $this->em->flush();

        $this->flashSuccess('_message.card.deleted');

        if ($this->isAjax()) {
            $this->redrawCards();
            $this->setAjaxPostGet();
        } else {
            $this->redirect('this');
        }
    }

    public function handleToggleVisibility(int $id): void
    {
        $card = $this->em->getCardRepository()->find($id);
        if (!$card) {
            $this->errorNotFoundEntity($id);
        }

        if ($card->isHidden()) {
            $card->show();
        } else {
            $card->hide();
        }

        $this->em->flush();
        $this->flashSuccess('_message.card.updated');

        if ($this->isAjax()) {
            $this->redrawCards();
            $this->setAjaxPostGet();
        } else {
            $this->redirect('this');
        }
    }

    protected function createComponentCardForm(): Form
    {
        $form = $this->cardFormFactory->create();
        $form->onSuccess[] = function (Form $form): void {
            $values = (array)$form->getValues();

            $this->card->setTitle($values['title']);
            $this->card->setDescription($values['description']);

            $this->em->flush();

            $this->flashSuccess('_message.card.updated');
            $this->redirect('this');
        };

        return $form;
    }

    protected function createComponentUploadForm(): Form
    {
        $form = $this->uploadFormFactory->create();

        $form->onSuccess[] = function (Form $form): void {
            $values = (array)$form->getValues();

            if (!empty($values['files'])) {
                $files = $this->fileFacade->createFromJson($values['files'], '/card');
                foreach ($files as $file) {
                    $this->createGalleryFacade->create($file);
                }
            }

            $this->flashSuccess('_message.upload.success');
            $this->redirect('this');
        };

        return $form;
    }

    protected function createComponentDropzone(): DropzoneControl
    {
        $dropzone = $this->dropzoneFactory->create();
        $dropzone->acceptOnlyImages();
        return $dropzone;
    }

    public function redrawCards(): void
    {
        $this->redrawFlashes();
        $this->redrawControl('cards');
    }

}