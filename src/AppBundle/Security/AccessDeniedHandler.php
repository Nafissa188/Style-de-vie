<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Security\Core\Security;
use AppBundle\Entity\User;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $templating;

    public function __construct(EngineInterface $templating )
    {
      $this->templating = $templating;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
      $content="";
      return new Response($this->templating->render('@Twig/Exception/error403.html.twig',array("message" =>$content)),403);


    }
}
