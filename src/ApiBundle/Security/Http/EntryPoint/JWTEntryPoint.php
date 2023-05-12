<?php

namespace ApiBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Api\CoreBundle\Services\WServicesService;

/**
 * JWTEntryPoint
 *
 */
class JWTEntryPoint implements AuthenticationEntryPointInterface
{
//    protected $WServicesService;
    /** @var  WServicesService */
    protected $apiService;

    /**
     * JWTEntryPoint constructor.
     *
     */
    public function __construct($apiService) {
        $this->apiService = $apiService;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        // 1048: "Le paramètre token est invalide ou manquant."
        $this->apiService->returnError(1048);
    }
}
