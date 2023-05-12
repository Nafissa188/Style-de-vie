<?php

namespace AppBundle\Controller\BO;

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
     * @Route("/", name="attribut_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/attribut/index.html.twig');
    }

    /**
     * Lists all ajax attribut entities.
     *
     * @Route("/ajax", name="ajax_attribut_all")
     * @Method("GET")
     */
    public function ajaxAll()
    {
        $em = $this->getDoctrine()->getManager();
        $attributs = $em->getRepository('AppBundle:Attribut')->findAll();
        
        $arrayCollection = array();

        foreach($attributs as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'created_by' => !empty($item->getUser()) ? 
                        '<ul class="list-inline">
                            <li class="list-inline-item">
                                <img style="height: 2.5rem;border-radius: 50%;"  class="table-avatar" src="/img/avatar5.png"> '.$item->getUser()->getEmail().'
                            </li>
                        </ul>'
                 : "~",
                'type' => $item->getType() == "color" ? "Couleurs" : "valeurs",
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
     * Lists all ajax attributvalue entities.
     *
     * @Route("attribut/{id}/values", name="ajax_attribut_values")
     * @Method("GET")
     */
    public function ajaxAttributValues(Attribut $attribut)
    {
        
        $arrayCollection = array();
        foreach($attribut->getChildren() as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'created_by' => !empty($item->getUser()) ? 
                        '<ul class="list-inline">
                            <li class="list-inline-item">
                                <img style="height: 2.5rem;border-radius: 50%;"  class="table-avatar" src="/img/avatar5.png"> '.$item->getUser()->getEmail().'
                            </li>
                        </ul>'
                 : "~",
                'value' => $attribut->getType() == 'color' ? '<div class="img-circle" style="background-color: '.$item->getValue().'; width: 40px;height: 40px; border-radius:50%; border: 4px solid #000; text-align: center;"></div>'  : $item->getValue(),
                'action' => '
                    <a class="btn btn-success addEditAttributValue "  href="#" data-title="Modifer une valeur"  data-url="'.$this->generateUrl('attribut_value_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                    <a data-url="'.$this->generateUrl('attribut_value_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteAttributValue" ><i style="color:#fff" class="fa fa-trash"></i></a>
                '
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Creates a new attribut entity.
     *
     * @Route("/new", name="attribut_new")
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
            "data" => $this->container->get('templating')->render('@App/attribut/new.html.twig', array(
                'attribut' => $attribut,
                'form' => $form->createView()
            ))
        ]);
    }

        /**
     * Creates a new attribut value entity.
     *
     * @Route("attribut/{id}/new", name="attribut_value_new")
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
            "data" => $this->container->get('templating')->render('@App/attribut/new.html.twig', array(
                'attribut' => $attribut,
                'form' => $form->createView()
            ))
        ]);
    }

    /**
     * Finds and displays a attribut entity.
     *
     * @Route("/{id}", name="attribut_show")
     * @Method("GET")
     */
    public function showAction(Attribut $attribut)
    {

        return $this->render('@App/attribut/show.html.twig', array(
            'attribut' => $attribut
        ));
    }

    /**
     * Displays a form to edit an existing attribut entity.
     *
     * @Route("/{id}/edit", name="attribut_edit")
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
            "data" => $this->container->get('templating')->render('@App/attribut/edit.html.twig', array(
                'attribut' => $attribut,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }
    /**
     * Displays a form to edit an existing attributvalue entity.
     *
     * @Route("value/{id}/edit", name="attribut_value_edit")
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
            "data" => $this->container->get('templating')->render('@App/attribut/edit.html.twig', array(
                'attribut' => $attributValue,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }

    /**
     * Deletes a attribut entity.
     *
     * @Route("/{id}/delete", name="attribut_delete")
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
     * @Route("value/{id}/delete", name="attribut_value_delete")
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
