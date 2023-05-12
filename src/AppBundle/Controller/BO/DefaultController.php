<?php

namespace AppBundle\Controller\BO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\UserType;
use App\Entity\User;
use App\Entity\Version;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="bo_default_page")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/dashbord", name="homepage")
     */
    public function dashbordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $storageDepots = $em->getRepository('AppBundle:StorageDepot')->findAll();
        $suppliers = $em->getRepository('AppBundle:User')->findBy(array('clientType' => 2 ));

        return $this->render('AppBundle::default/index.html.twig',[
          'storageDepots' => $storageDepots,
          'suppliers' => $suppliers
          ] );
    }

    /**
     * @Route("/user/profile", name="admin_profile")
     */
    public function ProfileAction(Request $request ,  UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(UserType::class, $user);
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
            return $this->redirectToRoute('admin_profile');
        }
        return $this->render('AppBundle::default/profile.html.twig',['user'=>$user,'form' => $form->createView()] );
    }











}
