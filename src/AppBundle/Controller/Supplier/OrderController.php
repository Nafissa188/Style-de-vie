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
 * @Route("supplier_order")
 */
class OrderController extends Controller
{
    /**
     * Lists all order entities.
     *
     * @Route("/", name="supplier_order_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@App/supplierAccount/pages/order/index.html.twig');
    }

    /**
     * Finds and displays a order entity.
     *
     * @Route("/show", name="supplier_order_show")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('@App/supplierAccount/pages/order/show.html.twig');
    }


}
