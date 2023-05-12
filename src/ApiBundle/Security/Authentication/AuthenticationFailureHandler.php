<?php

namespace ApiBundle\Security\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * AuthenticationFailureHandler
 *
 */
class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface {

    const RESPONSE_CODE = 401;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;
    protected $errorsCodesConfig;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher, $errorsCodesConfig) {
        $this->dispatcher = $dispatcher;
        $this->errorsCodesConfig = $errorsCodesConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $data = [
            'code' => self::RESPONSE_CODE,
            'message' => $this->errorsCodesConfig[self::RESPONSE_CODE],
            'result' => null
        ];

        $response = new JsonResponse($data, self::RESPONSE_CODE);
        $event = new AuthenticationFailureEvent($exception ,$response , $request );

        $this->dispatcher->dispatch(Events::AUTHENTICATION_FAILURE, $event);

        return $event->getResponse();
    }

}
