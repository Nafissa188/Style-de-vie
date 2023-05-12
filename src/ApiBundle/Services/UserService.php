<?php

namespace ApiBundle\Services;

use ApiBundle\Mailer\Mailer;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use ShadeSoft\UserAgentParserBundle\Service\UserAgentParser;
use Symfony\Component\HttpFoundation\RequestStack;
use ShadeSoft\UserAgentParserBundle\ShadeSoftUserAgentParserBundle;

class UserService
{

    private $requestStack;
    private $em;
    private $userMailer;

    public function __construct(RequestStack $requestStack, EntityManager $em, Mailer $userMailer)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->userMailer = $userMailer;
    }

    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function prepareUserResult($user)
    {
        $result['id'] = $user->getId();
        // $result['municipalite']['id'] = $user->getMunicipalite()->getId();
        // $result['municipalite']['nom'] = $user->getMunicipalite()->getNom();
        // $result['municipalite']['gouvernorat'] = $user->getMunicipalite()->getGouvernorat();
        // $result['municipalite']['delegation'] = $user->getMunicipalite()->getDelegation();
        // $result['municipalite']['codepostal'] = $user->getMunicipalite()->getCodepostal();
        $result['username'] = $user->getUsername();
        $result['email'] = $user->getEmail();
        $result['firstName'] = $user->getFirstName();
        $result['lastName'] = $user->getLastName();
        $result['phone'] = $user->getPhone();
        $result['cin'] = $user->getCin();
        $result['code'] = $user->getCode();

        return $result;
    }


    public function prepareConfirmSessionToken($user, $request){
        $sessionConfirmToken = $this->Genere_Password(8);
        $user->setSessionConfirmToken($sessionConfirmToken);

        $this->em->persist($user);
        $this->em->flush();

        //HISTORY
        $now = new \DateTime();
        $newSessionConfirmTokenHistory = new SessionConfirmTokenHistory();
        $newSessionConfirmTokenHistory->setAdminId($user->getId());
        $newSessionConfirmTokenHistory->setSessionConfirmToken($sessionConfirmToken);
        $newSessionConfirmTokenHistory->setUserIpAddress($request->server->get('REMOTE_ADDR'));
        $newSessionConfirmTokenHistory->setUserBrowser($request->server->get('HTTP_USER_AGENT'));

//        $browser = $this->getBrowser($request->server->get('HTTP_USER_AGENT'));
//        $newSessionConfirmTokenHistory->setBrowserName($browser['name']);
//        $newSessionConfirmTokenHistory->setBrowserVersion($browser['version']);
//        $newSessionConfirmTokenHistory->setBrowserPlatform($browser['platform']);

        $ua = $request->headers->get('User-Agent');

        $browser = $this->uaParser->getBrowser($ua);
        $browserName    = $browser['name'];
        $browserVersion = $browser['version'];

        $os = $this->uaParser->getOS($ua);
        $osName     = $os['name'];
        $osVersion  = $os['version'];

        $newSessionConfirmTokenHistory->setBrowserName($browser['name']);
        $newSessionConfirmTokenHistory->setBrowserVersion($browser['version']);
        $newSessionConfirmTokenHistory->setBrowserPlatform($os['name']);

        $newSessionConfirmTokenHistory->setDateAdd($now);
        $this->em->persist($newSessionConfirmTokenHistory);
        $this->em->flush();

        $this->userMailer->sendTokenConfirmSessionEmailMessage($user);
    }


    //generate random token to confirm session
    function Genere_Password($size)
    {
        // Initialisation des caractères utilisables
//        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $confirmSessionToken = '';
        for($i=0;$i<$size;$i++)
        {
            $confirmSessionToken.= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }

        return $confirmSessionToken;
    }

}
