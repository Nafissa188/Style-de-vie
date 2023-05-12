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
 * Security Controller
 * @Rest\Route("/data")
 */
class DataController extends FOSRestController
{

    /**
     * @var WServicesService
     */
    protected $apiService;

    public function preExecute() {
        $this->apiService = $this->get('px_core_ws_service');
    }

    /**
     * Ce WS permet de retourner la liste des Arrondissements pour l'agent actuel
     * @Rest\Get("/storage-depot", name="storage_depot", options={ "method_prefix" = false })
     * @ApiDoc(
     *      section="Data",
     *      description="Get list of all Storage Depot",
     * )
     *
     */
    public function getStorageDepotAction(Request $request) {
        $apiService = $this->get('px_core_ws_service');

        $arrondisements = $this->getDoctrine()->getRepository('AppBundle:StorageDepot')->findAll();

        return $apiService->renderResponse($arrondisements);
    }

}
