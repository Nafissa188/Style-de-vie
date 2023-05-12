<?php

namespace AppBundle\Controller\Supplier;

use AppBundle\Entity\Attribut;
use AppBundle\Entity\AttributValue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Attribut controller.
 *
 * @Route("attribut")
 */
class AttributController extends Controller
{
    /**
     * Lists all attribut entities.
     *
     * @Route("/", name="supplier_attribut_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/supplierAccount/pages/attribut/index.html.twig');
    }

    /**
     * Lists all ajax attribut entities.
     *
     * @Route("/ajax", name="supplier_ajax_attribut_all")
     * @Method("GET")
     */
    public function ajaxAll()
    {
        $em = $this->getDoctrine()->getManager();
        $attributs = $em->getRepository('AppBundle:Attribut')->findAll();
        
        $arrayCollection = array();

        foreach($attributs as $item) {
            $action = '<a class="btn btn-primary "  href="'.$this->generateUrl('supplier_attribut_show', ['id' => $item->getId()]).'" ><i style="color:#fff" class="fa fa-eye"></i></a>';
            if( null !== $item->getUser() && $this->container->get('security.token_storage')->getToken()->getUser()->getId() == $item->getUser()->getId()){
                $action .= '
                <a class="btn btn-success addEditAttribut "  href="#" data-title="Modifer un attribut"  data-url="'.$this->generateUrl('supplier_attribut_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                <a data-url="'.$this->generateUrl('supplier_attribut_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteAttribut" ><i style="color:#fff" class="fa fa-trash"></i></a>
                ';  
            }
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'type' => $item->getType() == "color" ? "Couleurs" : "valeurs",
                'action' => $action
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Lists all ajax attributvalue entities.
     *
     * @Route("attribut/{id}/values", name="supplier_ajax_attribut_values")
     * @Method("GET")
     */
    public function ajaxAttributValues(Attribut $attribut)
    {
        
        $arrayCollection = array();
        foreach($attribut->getChildren() as $item) {
            $action = '';
            if( null !== $item->getUser() && $this->container->get('security.token_storage')->getToken()->getUser()->getId() == $item->getUser()->getId()){
                $action = '
                    <a class="btn btn-success addEditAttributValue "  href="#" data-title="Modifer une valeur"  data-url="'.$this->generateUrl('supplier_attribut_value_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                    <a data-url="'.$this->generateUrl('supplier_attribut_value_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteAttributValue" ><i style="color:#fff" class="fa fa-trash"></i></a>
                ';  
            }
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'value' => $attribut->getType() == 'color' ? '<div class="img-circle" style="background-color: '.$item->getValue().'; width: 40px;height: 40px; border-radius:50%; border: 4px solid #000; text-align: center;"></div>'  : $item->getValue(),
                'action' => $action
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Creates a new attribut entity.
     *
     * @Route("/new", name="supplier_attribut_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $attribut = new Attribut();
        $form = $this->createForm('AppBundle\Form\AttributType', $attribut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $attribut->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            $em->persist($attribut);
            $em->flush();
            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/attribut/new.html.twig', array(
                'attribut' => $attribut,
                'form' => $form->createView()
            ))
        ]);
    }

        /**
     * Creates a new attribut value entity.
     *
     * @Route("attribut/{id}/new", name="supplier_attribut_value_new")
     * @Method({"GET", "POST"})
     */
    public function newValueAction(Request $request, Attribut $attribut)
    {
        $attribut_value = new AttributValue();
        $form = $this->createForm('AppBundle\Form\AttributValueType', $attribut_value, array('type' => $attribut->getType()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $attribut_value->setAttribut($attribut);
            $attribut_value->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            $em->persist($attribut_value);
            $em->flush();
            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/attribut/new.html.twig', array(
                'attribut' => $attribut,
                'form' => $form->createView()
            ))
        ]);
    }

    /**
     * Finds and displays a attribut entity.
     *
     * @Route("/{id}", name="supplier_attribut_show")
     * @Method("GET")
     */
    public function showAction(Attribut $attribut)
    {

        return $this->render('@App/supplierAccount/pages/attribut/show.html.twig', array(
            'attribut' => $attribut
        ));
    }

    /**
     * Displays a form to edit an existing attribut entity.
     *
     * @Route("/{id}/edit", name="supplier_attribut_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Attribut $attribut)
    {
        $editForm = $this->createForm('AppBundle\Form\AttributType', $attribut);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/attribut/edit.html.twig', array(
                'attribut' => $attribut,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }
    /**
     * Displays a form to edit an existing attributvalue entity.
     *
     * @Route("value/{id}/edit", name="supplier_attribut_value_edit")
     * @Method({"GET", "POST"})
     */
    public function editValueAction(Request $request, AttributValue $attributValue)
    {
        $editForm = $this->createForm('AppBundle\Form\AttributValueType', $attributValue, array('type' => $attributValue->getAttribut()->getType()));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/attribut/edit.html.twig', array(
                'attribut' => $attributValue,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }

    /**
     * Deletes a attribut entity.
     *
     * @Route("/{id}/delete", name="supplier_attribut_delete")
     * @Method("DELETE")
     */
    public function deleteAction( Attribut $attribut)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($attribut->getChildren() as $child) {
            $em->remove($child);
            $em->flush();
        }
        $em->remove($attribut);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }

    /**
     * Deletes a attribut entity.
     *
     * @Route("value/{id}/delete", name="supplier_attribut_value_delete")
     * @Method("DELETE")
     */
    public function deleteValueAction( AttributValue $attributValue)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($attributValue);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }

}
