<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 1/11/2018
 * Time: 4:18 PM
 */

namespace App\ExceptionListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class AjaxExceptionListener {
    private $isDev;

    public function __construct(ParameterBagInterface $parameterBag){
        $this->isDev = $parameterBag->get("kernel.environment") === "dev";
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {
        if (!$this->isDev) {
            $exception = $event->getException();
            $request = $event->getRequest();
            if ($request->getContentType() == "json") {
                $rtn = [
                    "status" => "failure",
                    "error" => $exception->getMessage()
                ];
                $event->setResponse(new JsonResponse($rtn));
            }
        }
    }
}