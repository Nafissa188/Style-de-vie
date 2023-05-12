<?php

namespace ApiBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;
use ApiBundle\Form\GenericType;
use FOS\RestBundle\View\View as FOSView;
use ApiBundle\Exception\ApiHttpException;

class WServicesService
{

    private $requestStack;
    private $formFactory;
    private $fos_rest_view_handler;
    private $fosView;
    private $errorsCodesConfig;

    public function __construct(RequestStack $requestStack, $formFactory, $fos_rest_view_handler, $errorsCodesConfig)
    {
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
        $this->fos_rest_view_handler = $fos_rest_view_handler;
        $this->fosView = FOSView::create();
        $this->errorsCodesConfig = $errorsCodesConfig;
    }

    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /*
     * Préparation de la réponse
     */

    public function renderResponse($data = null, $statusCode = 200)
    {
        $envelope['code'] = 0;
        $envelope['message'] = null;
        $envelope['result'] = $data;
        $this->fosView->setStatusCode($statusCode)->setData($envelope);
        $this->fosView->setFormat('json');
        $this->fosView->setHeader('Access-Control-Allow-Origin', '*');
        $response = $this->fos_rest_view_handler->handle($this->fosView);
        return $response;
    }

    /*
     * Préparation de la réponse d'erreure
     */

    public function renderErrorResponse($code, $message, $statusCode = 200)
    {
        $envelope['code'] = $code;
        $envelope['message'] = $message;
        $envelope['result'] = null;

        $this->fosView->setStatusCode($statusCode)->setData($envelope);
        $this->fosView->setFormat('json');
        $this->fosView->setHeader('Access-Control-Allow-Origin', '*');
        $response = $this->fos_rest_view_handler->handle($this->fosView);

        return $response;
    }

    public function validateWSInputs($wsConfig, $option = array())
    {
        $request = $this->getRequest();
        $requestMethod = $request->getMethod();

        if ($requestMethod == 'POST') {
            $params = $request->request->all();
        } elseif ($requestMethod == 'GET' or $requestMethod == 'PUT' or $requestMethod == 'DELETE') {
            $params = $request->query->all();
        }
        $request->request->set('generic_type_validator', $params);
        $request->setMethod('POST');

        if (isset($parameters['entity_path'])) {
            $entityClassName = $parameters['entity_path'];
        }
        $form = $this->formFactory->create(new GenericType($wsConfig), null, $option);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            $this->getErrorMessages($form);
        }
        $request->setMethod($requestMethod);

        return;
    }

    public function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errorCode = $error->getCause()->getConstraint()->payload;
            if (!$errorCode) {
                $this->checkSpecialErrors($error, $errorCode);
            }

            $this->returnError($errorCode);

            break;
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors = $this->getErrorMessages($child);
                break;
            }
        }

        return $errors;
    }

    public function getEntityErrorMessages($errors = array())
    {
        foreach ($errors as $key => $error) {
            if (method_exists($error, 'getMessage')) {
                $errorCode = $error->getConstraint()->payload;
                $this->returnError($errorCode);
            }

            // 1000: "Notre serveur est en cours de maintenance. Prière de réessayer ultérieurement."
            $this->returnError(1000);
        }

        return true;
    }

    public function returnError($errorCode, $customMsg = null)
    {
        if($customMsg) {
            $errorMsg = $customMsg;
        } else {
            if (!isset($this->errorsCodesConfig[$errorCode])) {
                // 1000: "Notre serveur est en cours de maintenance. Prière de réessayer ultérieurement."
                $errorCode = 1000;
            }
            $errorMsg = $this->errorsCodesConfig[$errorCode];
        }
        throw new ApiHttpException($errorCode, $errorMsg);
    }

    private function checkSpecialErrors($error, &$errorCode)
    {
        $errorCode = null;
        if ($error->getMessageTemplate() == "fos_user.password.mismatch") {
            // 1017: "Les deux mots de passe ne sont pas identiques."
            $errorCode = 1017;
        } else {
            $errorPropertyPath = $error->getCause()->getPropertyPath();
        }

        return true;
    }

}
