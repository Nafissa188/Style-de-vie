<?php

namespace ApiBundle\Security\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use ApiBundle\Services\UserService;
use ApiBundle\Services\WServicesService;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * AuthenticationSuccessHandler
 *
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    /**
     * @var JWTManager
     */
    protected $jwtManager;

    /** @var  UserService */
    protected $pxApiUserService;

    /** @var  WServicesService */
    protected $apiService;

    /** @var  DocumentManager */
    protected $em;

    /**
     * @param JWTManager $jwtManager
     */
    public function __construct(JWTManager $jwtManager, $pxApiUserService, $apiService, $em)
    {
        $this->jwtManager = $jwtManager;
        $this->pxApiUserService = $pxApiUserService;
        $this->apiService = $apiService;
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        $jwt = $this->jwtManager->create($user);

        $data = ['token' => $jwt, 'user' => $this->pxApiUserService->prepareUserResult($user)];
        //$this->pxApiUserService->prepareConfirmSessionToken($user, $request);
        $response = $this->apiService->renderResponse($data);

        return $response;
    }

}
