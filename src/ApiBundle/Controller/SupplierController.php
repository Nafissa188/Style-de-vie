<?php

namespace ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Api\CoreBundle\Services\WServicesService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\StorageDepot;

/**
 * Supplier Controller
 * @Rest\Route("/supplier")
 */
class SupplierController extends FOSRestController
{

    /**
     * @var WServicesService
     */
    protected $apiService;

    public function preExecute() {
        $this->apiService = $this->get('px_core_ws_service');
    }

    /**
     * Ce WS permet de retourner la liste des fournisseurs
     * @Rest\Get("/liste", name="api_get_supplier_liste", options={ "method_prefix" = false })
     * @ApiDoc(
     *      section="Supplier",
     *      description="Get list of all Suppliers",
     * )
     *
     */
    public function getSuppliersAction(Request $request) {
        $apiService = $this->get('px_core_ws_service');

        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array('clientType' => 2 ));
        $resultUsers = array();
        if($users){
            foreach ($users as $user){
                $resultUser = [
                    "id" => $user->getId(),
                    "full_name" => $user->getFirstName().' '.$user->getLastName(),
                    "phone" => $user->getPhone(),
                    "company_name" => $user->getCompanyName(),
                    "company_phone" => $user->getCompanyPhone(),
                    "company_address" => $user->getCompanyAddress(),
                    "latitude" => $user->getAltitude(),
                    "longitude" => $user->getLongitude(),
                ];
                $resultUsers[] = $resultUser;
            }
        }
        return $apiService->renderResponse($resultUsers);
    }

}
