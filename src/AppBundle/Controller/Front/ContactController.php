<?php

namespace AppBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact_page")
     */
    public function indexAction()
    {
        $token = $this->get('security.token_storage')->getToken();
        if($token->getUser() == "anon."){
            $logged = false;
        }else{
            $logged = true;
        }
        return $this->render('@App/front/pages/contact.html.twig', ['logged' => $logged]);
    }
    

    /**
     * @Route("/submit", name="contact_page_submission")
     * @Method("POST")
     */

    public function submitContactFormAction(Request $request)
    {
        // Récupérer les données du formulaire
        $name = $request->get('name');
        $email = $request->get('email');
        $subject = $request->get('subject');
        $message = $request->get('message');

        // Traiter les données du formulaire
        // Par exemple, vous pouvez enregistrer les données dans la base de données
        $contact = new Contact();
        $contact->setNom($nom);
        $contact->setEmail($email);
        $contact->setSubject($subject);
        $contact->setMessage($message);
        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();

        // Rediriger vers une page de confirmation ou vers une autre page
        return $this->render('@App/front/pages/contact.html.twig');
    }

}
