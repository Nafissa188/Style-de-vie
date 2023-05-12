<?php

namespace ApiBundle\Handler;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ApiExceptionWrapperHandler {

    /**
     * @var mixed $errorsCodesConfig
     */
    protected $WServicesService;
    protected $errorsCodesConfig;

    /**
     * ApiExceptionWrapperHandler constructor.
     *
     * @param mixed $errorsCodesConfig
     */
    public function __construct($WServicesService, $errorsCodesConfig) {
        $this->WServicesService = $WServicesService;
        $this->errorsCodesConfig = $errorsCodesConfig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        if ($exception instanceof \ApiBundle\Exception\ApiHttpException) {
            //create response, set status code etc.
            $code = $exception->getCode();
            $error = $exception->getMessage();
            $response = $this->WServicesService->renderErrorResponse($code, $error);
            $event->setResponse($response); //event will stop propagating here. Will not call other listeners.
        }
    }

}
