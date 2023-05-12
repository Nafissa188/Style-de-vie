<?php

namespace AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class BecomeSupplierController extends Controller
{
    /**
     * @Route("/become-supplier", name="become_supplier")
     */
    public function indexAction(Request $request)
    {
        $token = $this->get('security.token_storage')->getToken();
        if($token->getUser() == "anon."){
            $logged = false;
        }else{
            $logged = true;
        }

        $user = new User();
        $form = $this->createForm('AppBundle\Form\SuppliersFrontType', $user );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->addRole('ROLE_SUPPLIERS');
            $user->setClientType(2);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(["success" => true, "data" => ""]);
        }

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
            return new JsonResponse([
                "success" => false, 
                "data" => $this->container->get('templating')->render('@App/front/pages/partials/become-supplier-form.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView()
                ))
            ]); 
         } else { 
            return $this->render('@App/front/pages/become-supplier.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'logged' => $logged
            ));
         } 

        
    }

}
