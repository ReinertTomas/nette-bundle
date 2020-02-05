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
        $basePath = $this->getHttpRequest()->getUrl()->getBasePath();
        $files = [];

        /** @var FileUpload $file */
        foreach ($this->getRequest()->getFiles() as $file) {
            $path = "{$this->dir}/{$file->getName()}";
            $file->move($path);
            $files[] = $path;
        }

        $this->sendJson([
            'status' => 200,
            'message' => 'It works!',
            'files' => $files
        ]);
    }

}