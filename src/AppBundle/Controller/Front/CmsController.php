<?php

namespace AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CmsController extends Controller
{
    /**
     * @Route("/privacy-policy", name="privacy_page")
     */
    public function indexAction()
    {
        $token = $this->get('security.token_storage')->getToken();
        if($token->getUser() == "anon."){
            $logged = false;
        }else{
            $logged = true;
        }
        return $this->render('@App/front/pages/privacy-policy.html.twig', ['logged' => $logged]);
    }


    /**
     * @Route("/term-condition", name="term_page")
     */
    public function termAction()
    {
        $token = $this->get('security.token_storage')->getToken();
        if($token->getUser() == "anon."){
            $logged = false;
        }else{
            $logged = true;
        }
        return $this->render('@App/front/pages/terms-and-condition.html.twig', ['logged' => $logged]);
    }

}
