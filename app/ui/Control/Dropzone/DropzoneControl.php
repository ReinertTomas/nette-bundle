<?php
declare(strict_types=1);

namespace App\UI\Control\Dropzone;

use App\Model\File\PathBuilder;
use App\UI\Control\BaseControl;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\FileUpload;

final class DropzoneControl extends BaseControl
{

    private const ACCEPT_IMAGES = 'image/*';

    private string $dir;

    private Form $form;

    private string $hidden;

    private ?string $acceptedFiles;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->hidden = 'files';
        $this->acceptedFiles = null;
    }

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    public function setHidden(string $hidden): void
    {
        $this->hidden = $hidden;
    }

    public function acceptOnlyImages(): void
    {
        $this->acceptedFiles = self::ACCEPT_IMAGES;
    }

    public function render(): void
    {
        $this->template->buttons = $this->getButtons();
        $this->template->hidden = $this->getHidden();
        $this->template->acceptedFiles = $this->acceptedFiles;
        $this->template->setFile(__DIR__ . '/templates/dropzone.latte');
        $this->template->render();
    }

    public function handlePost(): void
    {
        /** @var FileUpload[] $files */
        $files = $this->getPresenter()
            ->getRequest()
            ->getFiles();

        foreach ($files as $file) {
            if ($file->isOk()) {
                $file->move(
                    PathBuilder::create($this->dir)
                    ->addSuffix('/')
                    ->addSuffix($file->getName())
                    ->getPathAbs()
                );
            }
        }

        $this->getPresenter()
            ->sendJson([
                'message' => 'Upload success!'
            ]);
    }

    /**
     * @return array<string>
     */
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