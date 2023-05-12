<?php

namespace AppBundle\Controller\BO;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Productattribute controller.
 *
 * @Route("productattribute")
 */
class ProductAttributeController extends Controller
{
    /**
     * Lists all ajax product entities.
     *
     * @Route("/{id}/ajax", name="ajax_product_attribut_all")
     * @Method("GET")
     */
    public function ajaxAll(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:ProductAttribute')->findBy(['product' => $product]);
        
        $arrayCollection = array();

        foreach($products as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'ref' => $item->getReference(),
                'price' => $item->getPrice(),
                'qty' => $item->getQuantity(),
                'is_default' => $item->getIsDefault(),
                'ean13' => $item->getEan13(),
                'upc' => $item->getUpc(),
                'isbn' => $item->getIsbn(),
                'mpn' => $item->getMpn(),
                'slug' => $item->getSlug(),
                'attributs' => '',
                'action' => '
                    <a class="btn btn-success addEditDelinaison "  href="#" data-title="Modifer une declinaison"  data-url="'.$this->generateUrl('productattribute_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                    <a data-url="'.$this->generateUrl('productattribute_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteDelinaison" ><i style="color:#fff" class="fa fa-trash"></i></a>
                '
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Creates a new productAttribute entity.
     *
     * @Route("/{id}/new", name="productattribute_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Product $product)
    {
        $productAttribute = new Productattribute();
        $form = $this->createForm('AppBundle\Form\ProductAttributeType', $productAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $productAttribute->setProduct($product);
            $em->persist($productAttribute);
            $em->flush();

            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/product/productattribute/new.html.twig', array(
                'productAttribute' => $productAttribute,
                'form' => $form->createView(),
            ))
        ]);
    }

    /**
     * Displays a form to edit an existing productAttribute entity.
     *
     * @Route("/{id}/edit", name="productattribute_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductAttribute $productAttribute)
    {
        $editForm = $this->createForm('AppBundle\Form\ProductAttributeType', $productAttribute);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/product/productattribute/edit.html.twig', array(
                'productAttribute' => $productAttribute,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }

    /**
     * Deletes a productAttribute entity.
     *
     * @Route("/{id}/delete", name="productattribute_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProductAttribute $productAttribute)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($productAttribute);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }

}
