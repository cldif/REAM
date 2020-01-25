<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Tenant;

/**
* @Route("/")
*/
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="websiteIndex" , methods={"GET"})
     */
    public function index()
    {
    	//Get people who the identity card is missing
    	$repository = $this->getDoctrine()->getRepository(Tenant::class);
    	$tenants = $repository->findAll();
        $listTenants = array();

        $tenantPath = $this->getParameter('app.tenantPath');

        foreach($tenants as $tenant)
        {
            if(!file_exists($tenantPath.$tenant->getId().'/'.$tenant->getIdentityCard()))
            {
                array_push($listTenants, $tenant);
            }
        }

        return $this->render('index/index.html.twig', [
        	"tenants" => $listTenants
        ]);
    }
}
