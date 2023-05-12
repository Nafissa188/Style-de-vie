<?php

namespace AppBundle\Controller\BO;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * Lists all contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/contact/index.html.twig',array(
            'contact' => $contact,
        ));
    }



    /**
     * Lists all ajax contact entities.
     *
     * @Route("/ajax", name="ajax_contact_all")
     * @Method("GET")
     */
    public function ajaxAll()
    {
        $em = $this->getDoctrine()->getManager();
        $contacts = $em->getRepository('AppBundle:Contact')->findAll();
        
        $arrayCollection = array();

        foreach($contacts as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'email' => $item->getEmail(),
                'subject' => $item->getSubject(),
                'message' => $item->getMessage(),
                'action' => '
                <a class="btn btn-primary "  href="'.$this->generateUrl('attribut_show', ['id' => $item->getId()]).'" ><i style="color:#fff" class="fa fa-eye"></i></a>
                <a class="btn btn-success addEditAttribut "  href="#" data-title="Modifer un attribut"  data-url="'.$this->generateUrl('attribut_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                <a data-url="'.$this->generateUrl('attribut_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteAttribut" ><i style="color:#fff" class="fa fa-trash"></i></a>
                '
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
        
    }



   
    /**
     * Finds and displays a contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     */
    public function showAction(Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);

        return $this->render('@App/contact/show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    
}
