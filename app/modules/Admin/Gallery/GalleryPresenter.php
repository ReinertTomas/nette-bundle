<?php
declare(strict_types=1);

namespace App\Modules\Admin\Gallery;

use App\Domain\File\FileFacade;
use App\Model\Database\Entity\File;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Control\Dropzone\DropzoneControl;
use App\UI\Control\Dropzone\DropzoneFactory;
use App\UI\Form\Gallery\GalleryFormFactory;
use Nette\Application\UI\Form;

class GalleryPresenter extends BaseAdminPresenter
{

    /** @var File[] */
    private array $files;

    /** @inject */
    public GalleryFormFactory $galleryFormFactory;

    /** @inject */
    public DropzoneFactory $dropzoneFactory;

    /** @inject */
    public FileFacade $fileFacade;

    public function actionDefault(): void
    {
        $this->files = $this->em->getFileRepository()
            ->findImages();

        /** @var Form $form */
        $form = $this->getComponent('galleryForm');
        /** @var DropzoneControl $dropzone */
        $dropzone = $this->getComponent('dropzone');
        $dropzone->setForm($form);
    }

    public function renderDefault(): void
    {
        $this->template->files = $this->files;
    }

    protected function createComponentGalleryForm(): Form
    {
        $form = $this->galleryFormFactory->create();

        $form->onSuccess[] = function (Form $form): void {
            $values = (array)$form->getValues();

            if (!empty($values['files'])) {
                $this->fileFacade->createFromJson($values['files']);
            }

            $this->flashSuccess('_message.upload.success');
            $this->redirect('this');
        };

        return $form;
    }

    protected function createComponentDropzone(): DropzoneControl
    {
        return $this->dropzoneFactory->create();
    }

}