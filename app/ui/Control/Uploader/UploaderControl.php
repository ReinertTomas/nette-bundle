<?php
declare(strict_types=1);

namespace App\UI\Control\Uploader;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\IResponse;

final class UploaderControl extends Control
{

    private string $dirUpload;

    private Form $form;

    private string $hidden;

    public function __construct(string $dirUpload)
    {
        $this->dirUpload = $dirUpload;
        $this->hidden = 'files';
    }

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    public function setHidden(string $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function renderModal(): void
    {
        $this->template->render(__DIR__ . '/templates/modal.latte');
    }

    public function renderButton(): void
    {
        $this->template->render(__DIR__ . '/templates/button.latte');
    }

    public function renderDropzone()
    {
        $this->template->buttons = $this->getButtons();
        $this->template->hidden = $this->getHidden();
        $this->template->render(__DIR__ . '/templates/dropzone.latte');
    }

    public function handleUpload(): void
    {
        $handler = new UploaderHandler(['target_dir' => $this->dirUpload]);
        $handler->sendNoCacheHeaders();
        $handler->sendCORSHeaders();

        $result = $handler->handleUpload();

        if ($result) {
            $response = [
                'status' => IResponse::S200_OK,
                'message' => $result
            ];
        } else {
            $response = [
                'status' => IResponse::S500_INTERNAL_SERVER_ERROR,
                'message' => $handler->getErrorMessage(),
                'code' => $handler->getErrorCode()
            ];
        }

        $this->getPresenter()
            ->sendJson($response);
    }

    private function getButtons(): array
    {
        $buttons = [];
        /** @var SubmitButton $submit */
        foreach ($this->form->getComponents(true, SubmitButton::class) as $submit) {
            $buttons[] = $submit->getHtmlId();
        }
        return $buttons;
    }

    private function getHidden(): string
    {
        /** @var HiddenField $hidden */
        $hidden = $this->form->getComponent($this->hidden);
        return $hidden->getHtmlId();
    }

}