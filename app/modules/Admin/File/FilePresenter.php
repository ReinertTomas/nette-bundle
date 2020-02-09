<?php
declare(strict_types=1);

namespace App\Modules\Admin\File;

use App\Modules\Admin\BaseAdminPresenter;
use Nette\Http\FileUpload;

class FilePresenter extends BaseAdminPresenter
{

    private string $dir;

    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    public function actionPost(): void
    {
        /** @var FileUpload $file */
        foreach ($this->getRequest()->getFiles() as $file) {
            if ($file->isOk()) {
                $file->move("{$this->dir}/{$file->getName()}");
            }
        }

        $this->sendJson([
            'message' => 'It works!'
        ]);
    }

}