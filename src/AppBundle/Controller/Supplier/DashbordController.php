<?php

namespace AppBundle\Controller\Supplier;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Form\UserType;
use App\Entity\Version;

/**
 * Suppliers controller.
 *
 * @IsGranted("ROLE_SUPPLIERS")
 */
class DashbordController extends Controller
{
    /**
     * Lists all Suppliers entities.
     *
     * @Route("/", name="supplier_dashbord")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/supplierAccount/pages/dashbord.html.twig');
    }

    /**
     * @Route("/profile", name="supplier_profile")
     */
    public function ProfileAction(Request $request ,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form =  $this->createForm('AppBundle\Form\SuppliersProfile', $user , array( 'user' => $this->container->get('security.token_storage')->getToken()->getUser() ) );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $form->get('plainPassword')->getData();
            if ($pass) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "Votre profile a été modifié");
            return $this->redirectToRoute('supplier_profile');
        }
        return $this->render('AppBundle::supplierAccount/pages/profile.html.twig',['user'=>$user,'form' => $form->createView()] );
    }
}


