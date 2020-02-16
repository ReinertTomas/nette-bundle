<?php
declare(strict_types=1);

namespace App\Modules\Admin\Example;

use App\Domain\Order\Event\OrderCreated;
use App\Model\Utils\DateTime;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Control\Dropzone\DropzoneControl;
use App\UI\Control\Dropzone\DropzoneFactory;
use App\UI\Form\FormFactory;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\Button;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ublaboo\DataGrid\DataGrid;

class ExamplePresenter extends BaseAdminPresenter
{

    /** @inject */
    public FormFactory $formFactory;

    /** @inject */
    public EventDispatcherInterface $dispatcher;

    /** @inject */
    public DropzoneFactory $dropzoneFactory;

    /**
     * @var array<array>
     */
    private array $data;

    public function actionDatagrid(): void
    {
        $this->data = json_decode(file_get_contents("./table.json"), true);
    }

    public function actionUpload(): void
    {
        /** @var Form $form */
        $form = $this->getComponent('fileForm');
        /** @var DropzoneControl $dropzone */
        $dropzone = $this->getComponent('dropzone');
        $dropzone->setForm($form);
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();

        $this->template->datetime = new DateTime();
    }

    protected function createComponentSimpleGrid(string $name): DataGrid
    {
        $grid = $this->gridFactory->create($this, $name);

        $grid->setDataSource($this->data);
        $grid->addColumnText('id', 'Id')
            ->setSortable();
        $grid->addColumnText('name', 'Name');
        $grid->addColumnText('position', 'Position');
        $grid->addColumnText('salary', 'Salary');
        $grid->addColumnText('date', 'Date');
        $grid->addColumnText('office', 'Office');
        $grid->addColumnText('number', 'Number');

        return $grid;
    }

    protected function createComponentOrderForm(): Form
    {
        $form = $this->formFactory->createSecured();
        $form->addText('order', 'Order name')
            ->setRequired(true);
        $form->addSubmit('send', 'OK');
        $form->onSuccess[] = function (Form $form): void {
            $this->dispatcher->dispatch(new OrderCreated($form->values->order));
        };
        return $form;
    }

    protected function createComponentSimpleForm(): Form {
        $form = $this->formFactory->createSecured();

        $form->addText('text', 'Text')
            ->setRequired();
        $form->addText('email', 'Email')
            ->setRequired();

        $form->addSubmit('submitSuccess', 'Success');
        $form->addSubmit('submitError', 'Error')
            ->setValidationScope(null)
            ->onClick[] = function (Button $button): void {
            $button->getForm()->addError("Error");
            $this->flashError("Form submit error.");
        };

        $form->onSuccess[] = function (Form $form): void {
            $this->flashSuccess("Form submit success.");
        };

        return $form;
    }

    protected function createComponentAjaxForm(): Form {
        $form = $this->formFactory->createSecured();

        $form->addText('text', 'Text')
            ->setRequired();
        $form->addText('email', 'Email')
            ->setRequired();

        $form->addSubmit('submitSuccess', 'Success');
        $form->addSubmit('submitError', 'Error')
            ->setValidationScope(null)
            ->onClick[] = function (Button $button): void {
            $button->getForm()->addError("Error");
            $this->flashError("Form submit error.");
            $this->redrawFlashes();
        };

        $form->onSuccess[] = function (Form $form): void {
            $this->flashSuccess("Form submit success.");

            if ($this->isAjax()) {
                $this->redrawFlashes();
            }
        };

        return $form;
    }

    protected function createComponentModalForm(): Form {
        $form = $this->formFactory->createSecured();

        $form->addText('text', 'Text')
            ->setRequired();

        $form->addSubmit('submitRefresh', 'Refresh');

        $form->onSuccess[] = function (Form $form): void {
            if ($this->isAjax()) {
                $this->redrawModalContent();
            } else {
                $this->redirect('this');
            }
        };

        return $form;
    }

    protected function createComponentFileForm(): Form
    {
        $form = $this->formFactory->createSecured();

        $form->addText('name', 'Name');
        $form->addHidden('files');
        $form->addSubmit('submit', 'Submit');

        $form->onSuccess[] = function (Form $form): void {
            $values = (array)$form->getValues();
            $files = $values['files'] ?? "";
            dump($values);
            dump(json_decode($files));
            die();
        };

        return $form;
    }

    protected function createComponentDropzone(): DropzoneControl
    {
        return $this->dropzoneFactory->create();
    }

    public function handleRefresh(?string $snippet): void
    {
        if ($this->isAjax()) {
            $this->redrawControl($snippet);
        } else {
            $this->redirect('this');
        }
    }

}