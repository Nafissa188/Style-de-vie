<?php

namespace AppBundle\Controller\Supplier;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Product controller.
 *
 * @Route("productImage")
 */
class ProductImageController extends Controller
{

    /**
     * Lists all ajax product entities.
     *
     * @Route("/{id}/images/add", name="supplier_ajax_product_add_image")
     * @Method({"GET", "POST"})
     */
    public function ajaxAddImages(Product $product, Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $document = new ProductImage();
        $media = $request->files->get('file');
        $document->setIsCover(false);
        $document->setProduct($product);
        $document->setFile($media);
        $document->setPath($media->getPathName());
        $document->setName($media->getClientOriginalName());
        $document->upload();
        $em->persist($document);
        $em->flush();

        return new JsonResponse(array('id' => $document->getId()));
    }

    /**
     * Lists all ajax product imagesentities.
     *
     * @Route("/{id}/images/all", name="supplier_ajax_product_image")
     * @Method({"GET", "POST"})
     */
    public function ajaxAllImages(Product $product)
    {
        $arrayCollection = array();
        $images = $product->getImages();
        foreach ($images as $item) {
            $arrayCollection[] = array(
                'name' => $item->getId(),
                'path'=> '/'.$item->getUploadDir().'/'.$item->getName() ,
                'size' => 12345
            );
        }

        return new JsonResponse($arrayCollection);
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/delete", name="supplier_product_image_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProductImage $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }


    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="supplier_product_image_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductImage $image)
    {
        $editForm = $this->createForm('AppBundle\Form\ProductImageType', $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if($image->getIsCover()){
                $em = $this->getDoctrine()->getManager();
                $images = $em->getRepository('AppBundle:ProductImage')->findBy(array('product' => $image->getProduct()));
                foreach ($images as $item) {
                    if($item->getId() != $image->getId()){
                        $item->setIsCover(false);
                        $em->persist($item);
                        $em->flush();
                    }
                }
                $data = '/'.$image->getUploadDir().'/'.$image->getName();
            }else{
                $data = "";
            }
            return new JsonResponse(["success" => true, "data" => $data]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/product/partials/editImage.html.twig', array(
                'image' => $image,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }

}