<?php

namespace AppBundle\Controller\BO;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Suppliers controller.
 *
 * @Route("suppliers")
 * @IsGranted("ROLE_ADMIN")
 */
class SuppliersController extends Controller
{
    /**
     * Lists all Suppliers entities.
     *
     * @Route("/", name="suppliers_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findBy(array('clientType' => 2 ));

        $form = $this->createForm('AppBundle\Form\UserSearchType', null , array( 'userr' => $this->container->get('security.token_storage')->getToken()->getUser()) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $data = $form->getData();
          $ussers = [];

        }else{
          $ussers = $users;
        }

        return $this->render('@App/suppliers/index.html.twig', array(
            'users' => $ussers,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new suppliers entity.
     *
     * @Route("/new", name="suppliers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\SuppliersType', $user , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() ) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->addRole('ROLE_SUPPLIERS');
            $user->setClientType(2);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('suppliers_index');
        }

        return $this->render('@App/suppliers/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a suppliers entity.
     *
     * @Route("/{id}", name="suppliers_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {

        return $this->render('@App/suppliers/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing suppliers entity.
     *
     * @Route("/{id}/edit", name="suppliers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user ,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $editForm = $this->createForm('AppBundle\Form\SuppliersType', $user , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() ) );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pass = $editForm->get('plainPassword')->getData();
            if ($pass) {
              $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
              $user->setPassword($password);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('suppliers_index');
        }

        return $this->render('@App/suppliers/edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }


    /**
     * Deletes a suppliers entity.
     *
     * @Route("/{id}/delete", name="suppliers_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('suppliers_index');
    }

    /**
     * Enable a suppliers entity.
     *
     * @Route("/{id}/enable", name="suppliers_enable")
     * @Method({"GET","POST"})
     */
    public function enableAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(true) ;
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('suppliers_index');
    }

    /**
     * Desable a suppliers entity.
     *
     * @Route("/{id}/desable", name="suppliers_desable")
     * @Method({"GET","POST"})
     */
    public function desableAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(false) ;
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('suppliers_index');
    }
}
