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
 * User controller.
 *
 * @Route("user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findBy(array('clientType' => 1 ));
        $form = $this->createForm('AppBundle\Form\UserSearchType', null , array( 'userr' => $this->container->get('security.token_storage')->getToken()->getUser()) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $data = $form->getData();
          $ussers = [];

        }else{
          $ussers = $users;
        }

        return $this->render('@App/user/index.html.twig', array(
            'users' => $ussers,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserAdminType', $user , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() ) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->addRole('ROLE_ADMIN');
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@App/user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->render('@App/user/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user ,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $editForm = $this->createForm('AppBundle\Form\EditUserAdminType', $user , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() ) );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pass = $editForm->get('plainPassword')->getData();
            if ($pass) {
              $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
              $user->setPassword($password);
            }
            $user->addRole('ROLE_ADMIN');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@App/user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }


    /**
     * Deletes a user entity.
     *
     * @Route("/{id}/delete", name="user_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * Enable a user entity.
     *
     * @Route("/{id}/enable", name="user_enable")
     * @Method({"GET","POST"})
     */
    public function enableAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(true) ;
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * Desable a user entity.
     *
     * @Route("/{id}/desable", name="user_desable")
     * @Method({"GET","POST"})
     */
    public function desableAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled(false) ;
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }
}
