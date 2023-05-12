<?php

namespace ApiBundle\Controller;

use Backend\UserBundle\Form\ResettingFormType;
use ApiBundle\Form\ChangePasswordFormType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controller managing the resetting and the retrieving of the password
 * @Rest\Route("/user")
 */
class ResettingController extends FOSRestController {


    protected $apiService;
    protected $userParams;

    public function preExecute()
    {
        $this->apiService = $this->get('px_core_ws_service');
        $this->userParams = $this->getParameter('user_ws_config');
    }

    /**
     * Ce WS permet de réinitialiser le mot de passe oublié d'un utilisateur
     * ```
     * ** ERROR CODE **
     * 1008: Le paramètre email est invalide ou manquant.
     *
     * ```
     *
     * @Rest\Post("/reset-password/step1", name="user_reset_password_step1", options={ "method_prefix" = false })
     * @ApiDoc(
     *  section="Users",
     *  description="Reset user's password step 1",
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="user's email"},
     *  },
     * )
     */
    public function resetPassStepOneAction(Request $request) {
        $apiService = $this->get('px_core_ws_service');

        $email = $request->request->get('email');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            $response = $apiService->returnError(1008);   // 6: Le paramètre email est invalide ou manquant.
            return $response;
        }

        $confirmationToken = $this->generateToken();
        $user->setConfirmationToken($confirmationToken);

        $this->get('px_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        $result = 'ok';
        $response = $apiService->renderResponse($result);

        return $response;
    }

    protected function generateToken() {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        do {
            $x = 9; // Amount of digits
            $min = pow(10, $x);
            $max = (pow(10, $x + 1) - 1);
            $token = rand($min, $max);

            $user = $userManager->findUserByConfirmationToken($token);
        } while ($user);

        return $token;
        //TODO: Upadte generate token AlphaNum
        /*$length = 20;
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;*/
    }


    /**
     * Ce WS permet d'enregistrer un nouveau recensement
     * ```
     * ** ERROR CODE **
     * 1601: Le paramètre Agent ID est invalide ou manquant.
     * 1602: Impossible de trouver le compte agent.
     * 1603: Impossible de faire la correspondance avec le profil agent.
     * 1603: Le paramètre playerId est invalide ou manquant.
     *
     * ```
     * @Rest\Post("/playerid/{id}", name="user_palyer_id", options={ "method_prefix" = false })
     * @ApiDoc(
     *  section="Users",
     *  description="Get player_id for notification",
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="Agent ID"
     *          }
     *      },
     *  parameters={
     *      {"name"="playerId", "dataType"="string", "required"=true, "description"="identifiant of oneSignal"},
     *  },
     * )
     *
     */
    public function addPlayerIdAction(Request $request, $id) {
        $apiService = $this->get('px_core_ws_service');

        if(empty($id) || $id == '{id}'){
            $apiService->returnError(1601);
        }

        /** @var User $user */
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if (!$user) {
            $apiService->returnError(1602); //Impossible de trouver le compte agent.
        }
        if ($user != $currentUser) {
            $apiService->returnError(1603); //Impossible de faire la correspondance avec le profil agent
        }

        if(empty($request->get('playerId'))){
            $apiService->returnError(1604);
        }
        $user->setPlayerId($request->get('playerId'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();


        $result['result'] = "ok";
        $result['id'] = $user->getId();
        $result['playerId'] = $user->getPlayerId();

        $response = $apiService->renderResponse($result);

        return $response;
    }

}
