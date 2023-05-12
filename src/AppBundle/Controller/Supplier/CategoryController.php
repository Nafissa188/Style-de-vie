<?php

namespace AppBundle\Controller\Supplier;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("supplier_category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="supplier_category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/supplierAccount/pages/category/index.html.twig');
    }

    /**
     * Lists all ajax category entities.
     *
     * @Route("/ajax", name="supplier_ajax_category_all")
     * @Method("GET")
     */
    public function ajaxAll(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if( null === $request->get('parent')){
            $categories = $em->getRepository('AppBundle:Category')->findBy(["parent" => null ]);
        }else{
            $parent = $em->getRepository('AppBundle:Category')->find($request->get('parent'));
            $categories = $parent->getChildren();
        }
        
        $arrayCollection = array();

        foreach($categories as $item) {
            $action = '<a class="btn btn-primary "  href="'.$this->generateUrl('supplier_category_show', ['id' => $item->getId()]).'" ><i style="color:#fff" class="fa fa-eye"></i></a>';
            if( null !== $item->getUser() && $this->container->get('security.token_storage')->getToken()->getUser()->getId() == $item->getUser()->getId()){
                $action .= '
                <a class="btn btn-success addEditCategory "  href="#" data-title="Modifer une categorie"  data-url="'.$this->generateUrl('supplier_category_edit', ['id' => $item->getId()]).'"><i style="color:#fff" class="fa fa-pen"></i></a>
                <a data-url="'.$this->generateUrl('supplier_category_delete', ['id' => $item->getId()]).'" href="#"  class="btn btn-danger deleteCategory" ><i style="color:#fff" class="fa fa-trash"></i></a>
                ';  
            }

            $arrayCollection[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'action' => $action
            );
        }

        $response['data'] = !empty($arrayCollection) ? $arrayCollection : [];
        return new JsonResponse($response);
    }

    /**
     * Creates a new category entity.
     *
     * @Route("{parent}/new", name="supplier_category_new", defaults={"parent"=0})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Category $parent = null)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($parent){
                $category->setParent($parent);
            }
            $category->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            $em->persist($category);
            $em->flush();
            return new JsonResponse(["success" => true, "data" => ""]);
        }
        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/category/new.html.twig', array(
                'category' => $category,
                'form' => $form->createView()
            ))
        ]);
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="supplier_category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        return $this->render('@App/supplierAccount/pages/category/show.html.twig', array(
            'category' => $category,
            'parents' => $category->getAllParents(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="supplier_category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(["success" => true, "data" => ""]);
        }

        return new JsonResponse([
            "success" => false, 
            "data" => $this->container->get('templating')->render('@App/supplierAccount/pages/category/edit.html.twig', array(
                'category' => $category,
                'edit_form' => $editForm->createView(),
            ))
        ]);
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}/delete", name="supplier_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($category->getChildren() as $child) {
            $em->remove($child);
            $em->flush();
        }
        $em->remove($category);
        $em->flush();

        return new JsonResponse(["success" => true]);
    }
}
