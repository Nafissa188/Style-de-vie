<?php

namespace AppBundle\Controller\BO;

use AppBundle\Entity\StorageDepot;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * StorageDepot controller.
 *
 * @Route("storage_depot")
 * @IsGranted("ROLE_ADMIN")
 */
class StorageDepotController extends Controller
{
    /**
     * Lists all Storage Depot entities.
     *
     * @Route("/", name="storage_depot_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $storage_depots = $em->getRepository('AppBundle:StorageDepot')->findAll();

        return $this->render('@App/storageDepot/index.html.twig', array(
            'storage_depots' => $storage_depots
        ));
    }

    /**
     * Creates a new Storage Depot entity.
     *
     * @Route("/new", name="storage_depot_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $storageDepot = new StorageDepot();
        $form = $this->createForm('AppBundle\Form\StorageDepotType', $storageDepot , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() , 'services' => [] ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storageDepot);
            $em->flush();
            return $this->redirectToRoute('storage_depot_index');
        }

        return $this->render('@App/storageDepot/new.html.twig', array(
            'storageDepot' => $storageDepot,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Storage Depot entity.
     *
     * @Route("/{id}", name="storage_depot_show")
     * @Method("GET")
     */
    public function showAction(StorageDepot $Storage_depot)
    {
    }

    /**
     * Displays a form to edit an existing Storage Depot entity.
     *
     * @Route("/{id}/edit", name="storage_depot_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, StorageDepot $Storage_depot)
    {
        $editForm = $this->createForm('AppBundle\Form\StorageDepotType', $Storage_depot , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser()  , 'services' => []  ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $em = $this->getDoctrine()->getManager();
            $em->persist($Storage_depot);
            $em->flush();

            return $this->redirectToRoute('storage_depot_index');
        }


        return $this->render('@App/storageDepot/edit.html.twig', array(
            'storageDepot' => $Storage_depot,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Storage Depot entity.
     *
     * @Route("/{id}/delete", name="storage_depot_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, StorageDepot $Storage_depot)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($Storage_depot);
        $em->flush();

        return $this->redirectToRoute('storage_depot_index');
    }


}
