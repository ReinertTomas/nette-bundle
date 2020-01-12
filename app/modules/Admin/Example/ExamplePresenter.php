<?php
declare(strict_types=1);

namespace App\Modules\Admin\Example;

use App\Domain\Order\Event\OrderCreated;
use App\Model\Utils\DateTime;
use App\Modules\Admin\BaseAdminPresenter;
use Nette\Application\UI\Form;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ublaboo\DataGrid\DataGrid;

class ExamplePresenter extends BaseAdminPresenter
{

    /** @inject */
    public EventDispatcherInterface $dispatcher;

    private array $data;

    public function actionDatagrid(): void
    {
        $this->data = json_decode(file_get_contents("./table.json"), true);
    }

    protected function beforeRender()
    {
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
        $form = new Form();
        $form->addText('order', 'Order name')
            ->setRequired(true);
        $form->addSubmit('send', 'OK');
        $form->onSuccess[] = function (Form $form): void {
            $this->dispatcher->dispatch(new OrderCreated($form->values->order));
        };
        return $form;
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