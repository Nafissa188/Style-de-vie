<?php
namespace ApiBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JWTResponseListener
 */
class JWTResponseListener
{
    /**
     * Add public data to the authentication response
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'username' => $user->getUsername(),
            'roles'    => $user->getRoles()
        );

        $event->setData($data);
    }
}
