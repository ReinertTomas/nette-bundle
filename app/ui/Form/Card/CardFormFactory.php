<?php
declare(strict_types=1);

namespace App\UI\Form\Card;

use App\Model\Database\Entity\Card;
use App\UI\Form\FormFactory;
use Nette\Application\UI\Form;

final class CardFormFactory
{

    private FormFactory $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(): Form
    {
        $form = $this->formFactory->createSecured();

        $form->addText('title', 'Title')
            ->setRequired();
        $form->addText('description', 'Description')
            ->setRequired();
        $form->addSubmit('submit');

        return $form;
    }

    public function setDefaults(Form $form, Card $card): void
    {
        $form->setDefaults([
            'title' => $card->getTitle(),
            'description' => $card->getDescription()
        ]);
    }

}