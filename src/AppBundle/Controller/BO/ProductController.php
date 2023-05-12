<?php

namespace AppBundle\Controller\BO;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/product/index.html.twig');
    }

    /**
     * Lists all ajax product entities.
     *
     * @Route("/ajax", name="ajax_product_all")
     * @Method("GET")
     */
    public function ajaxAll()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')->findAll();
        
        $arrayCollection = array();

        foreach($products as $item) {
            $categorys = '<div style="display: grid;display: grid;grid-template-columns: 100px 100px 100px;grid-gap: 10px;">';
            foreach($item->getCategorys() as $categ){
                $categorys .= ' <span class="badge badge-success">'.$categ->getName().'</span> ';
            }
            $categorys .= ' </div> ';
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'image' => '<ul class="list-inline">
                    <li class="list-inline-item">
                        <img style="height: 2.5rem;"  class="table-avatar" src="'.$item->getCoverImage().'">
                    </li>
                </ul>',
                'created_by' => !empty($item->getUser()) ? 
                        '<ul class="list-inline">
                            <li class="list-inline-item">
                                <img style="height: 2.5rem;border-radius: 50%;"  class="table-avatar" src="/img/avatar5.png"> '.$item->getUser()->getEmail().'
                            </li>
                        </ul>'
                 : "~",
                'name' => $item->getName(),
                'type' => $item->getType(),
                'reference' => $item->getReference(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'categorys' => $categorys,
                'enabled' => $item->getEnabled() ? '<span class="badge badge-success">Oui</span>' : '<span class="badge badge-danger">Non</span>',
                'action' => '
                <a class="btn btn-primary "  href="'.$this->generateUrl('product_edit', ['id' => $item->getId()]).'" ><i style="color:#fff" class="fa fa-eye"></i></a>
                <a data-url="'.$this->generateUrl('product_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteProduct" ><i style="color:#fff" class="fa fa-trash"></i></a>
                '
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Lists all ajax categorys product entities.
     *
     * @Route("/{id}/categorys/all", name="ajax_category_tree")
     * @Method("GET")
     */
    public function ajaxAllCategoryTree(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $response = $em->getRepository('AppBundle:Product')->findAllTreeView($product);
        return new JsonResponse($response);
    }

    
    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setEnabled(false);
        $product->setUser($this->container->get('security.token_storage')->getToken()->getUser());
        $form = $this->createForm('AppBundle\Form\AddProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('@App/product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        return $this->render('@App/product/show.html.twig', array(
            'product' => $product
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $editForm = $this->createForm('AppBundle\Form\EditProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('@App/product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/description", name="product_edit_description")
     * @Method({"GET", "POST"})
     */
    public function editDescription(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setDescription($request->get('description'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/metatitle", name="product_edit_meta_title")
     * @Method({"GET", "POST"})
     */
    public function editMetaTitle(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setMetaTitle($request->get('metaTitle'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/metadescription", name="product_edit_meta_description")
     * @Method({"GET", "POST"})
     */
    public function editMetaDescription(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setMetaDescription($request->get('metaDescription'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/slug", name="product_edit_slug")
     * @Method({"GET", "POST"})
     */
    public function editSlug(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setSlug($request->get('slug'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/isbn", name="product_edit_isbn")
     * @Method({"GET", "POST"})
     */
    public function editIsbn(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setIsbn($request->get('isbn'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/ean13", name="product_edit_ean13")
     * @Method({"GET", "POST"})
     */
    public function editEan13(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setEan13($request->get('ean13'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/upc", name="product_edit_upc")
     * @Method({"GET", "POST"})
     */
    public function editUpc(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setUpc($request->get('upc'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit/mpn", name="product_edit_mpn")
     * @Method({"GET", "POST"})
     */
    public function editmpn(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setMpn($request->get('mpn'));
        $em->persist($product);
        $em->flush();
        return new JsonResponse(["success" => true]);
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}/delete", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }
}
