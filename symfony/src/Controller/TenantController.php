<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/", name="tenantIndex" , methods={"GET"})
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

            // On récupère le service validator
            $validator = $this->get('validator');
                
            // On déclenche la validation sur notre object
            $listErrors = $validator->validate($tenant->getFather());

            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                return new Response("L'annonce est valide !");
            }

            if($tenant->getFather() != NULL)
            {
                $tenant->getFather()->setGender("Homme");
                $entityManager->persist($tenant->getFather());
            }

            if($tenant->getMother() != NULL)
            {
                $tenant->getMother()->setGender("Femme");
                $entityManager->persist($tenant->getMother());
            }

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
     * @Route("/{id}/modify", name="modifyTenant", methods={"GET", "POST"})
     */
    public function modify(Request $request, Tenant $tenant)
    {
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('getTenant', array("id" => $tenant->getId()));
        }

        return $this->render('tenant/formAddTenant.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
	* @Route("/{id}", name="deleteTenant", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Tenant::class);
        $tenant = $repository->find($id);
    
        if($tenant)
        {
            $entityManager->remove($tenant);
            $entityManager->flush();
            $data = ['deleted' => "OK"];
        }
        else
        {
            $data = ['deleted' => "ERROR : Entity not found"];
        }
 
        return new JsonResponse($data);
    }
}
