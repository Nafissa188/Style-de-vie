<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Security Controller
 * @Rest\Route("/user")
 */
class SecurityController extends Controller {

    protected $apiService;
    protected $userParams;

    public function preExecute() {
        $this->apiService = $this->get('px_core_ws_service');
        $this->userParams = $this->getParameter('user_ws_config');
    }

    /**
     * Ce WS permet d'authentifier un utilisateur
     *
     * @Rest\Post("/login", name="user_login", options={ "method_prefix" = false })
     * @ApiDoc(
     *  section="Users",
     *  description="Connexion",
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="user's email"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="user's password"},
     *  },
     * )
     */
    public function checkAction() {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

}
