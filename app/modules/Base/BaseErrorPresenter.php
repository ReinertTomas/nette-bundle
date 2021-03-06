<?php
declare(strict_types=1);

namespace App\Modules\Base;

use Nette\Application\BadRequestException;
use Nette\Application\Helpers;
use Nette\Application\IResponse as AppResponse;
use Nette\Application\Request;
use Nette\Application\Responses\CallbackResponse;
use Nette\Application\Responses\ForwardResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Throwable;
use Tracy\ILogger;

abstract class BaseErrorPresenter extends UnsecuredPresenter
{
    private ILogger $logger;

    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }

    public function run(Request $request): AppResponse
    {
        $e = $request->getParameter('exception');

        if ($e instanceof Throwable) {
            $code = $e->getCode();
            $level = ($code >= 400 AND $code <= 499) ? ILogger::WARNING : ILogger::ERROR;

            $this->logger->log(sprintf(
                'Code %s: %s in %s:%s',
                $code,
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ), $level);
        }

        if ($e instanceof BadRequestException) {
            [$module, , $sep] = Helpers::splitName($request->getPresenterName());
            return new ForwardResponse($request->setPresenterName($module . $sep . 'Error4xx'));
        }

        return new CallbackResponse(function (IRequest $httpRequest, IResponse $httpResponse): void {
            $header = $httpResponse->getHeader('Content-Type');
            if ($header !== null && preg_match('#^text/html(?:;|$)#', $header)) {
                require __DIR__ . '/templates/500.phtml';
            }
        });
    }

}