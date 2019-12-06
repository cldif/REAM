<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Tenant;
use App\Form\TenantType;

/**
* @Route("/locataire")
*/
class TenantController extends AbstractController
{
    /**
     * @Route("/", name="tenantIndex")
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(Tenant::class);
    	$tenants = $repository->findAll();

        return $this->render('tenant/index.html.twig', [
            'tenants' => $tenants,
        ]);
    }

    /**
	* @Route("/add", name="addTenant", methods={"GET", "POST"})
	*/
    public function add(Request $request)
    {
	    $tenant = new Tenant();
	    $form = $this->createForm(TenantType::class, $tenant);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {

	        $tenant = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($tenant->getFather());
	        $entityManager->persist($tenant);
	        $entityManager->flush();

	        return $this->redirectToRoute('getTenant', array("id" => $tenant->getId()));
	    }

        return $this->render('tenant/formAddTenant.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getTenant", methods={"GET"}),
	requirements={"id" = "\d+"}))
	*/
    public function get($id)
    {
    	$repository = $this->getDoctrine()->getRepository(Tenant::class);
    	$tenant = $repository->find($id);

	   if (!$tenant) {
	        throw $this->createNotFoundException(
	            'No tenant found for id '.$id
	        );
	    }

        return $this->render('tenant/getTenant.html.twig', array("tenant"  => $tenant));
    }

    /**
	* @Route("/{id}", name="modifyTenant", methods={"POST"}, requirements={"id" = "\d+"})
	*/
    public function modify($id, Request $request)
    {


        return $this->render('tenant/getTenant.html.twig', array("tenant"  => $tenant));
    }

    /**
	* @Route("/{id}", name="modifyTenant", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        return $this->render('tenant/getTenant.html.twig', 
        	["id" => $id]);
    }
}
