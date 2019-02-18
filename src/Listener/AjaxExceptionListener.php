<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 1/11/2018
 * Time: 4:18 PM
 */

namespace App\Listener;

use App\Exception\ValidationException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxExceptionListener {
    private $isDev;

    public function __construct(ParameterBagInterface $parameterBag){
        $this->isDev = $parameterBag->get("kernel.environment") === "dev";
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        $request = $event->getRequest();
        if ($request->getContentType() == "json") {
            $code = $exception instanceof HttpException ? $exception->getStatusCode() : 400;
            $rtn = $exception instanceof \JsonSerializable ?
                $exception->jsonSerialize() : [
                    "status" => "failure",
                    "message" => $exception->getMessage()
                ];
            if ($this->isDev) {
                $rtn["stackTrace"] = explode("\n", $exception->getTraceAsString());
            }
            $event->setResponse(new JsonResponse($rtn, $code));
        }
    }
}