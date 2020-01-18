<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Tenant;
use App\Entity\Record;
use App\Form\TenantType;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormError;

/**
* @Route("/tenant")
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
    public function add(Request $request, ValidatorInterface $validator)
    {
	    $tenant = new Tenant();

	    $form = $this->createForm(TenantType::class, $tenant);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) 
        {
	        $tenant = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $dataParent = $form->get("parent")->getData();
            $errorFather = 0;
            $errorMother = 0;
            $errorsFather = [];
            $errorsMother = [];

            if($dataParent == "1" || $dataParent == "3")
            {
                $tenant->getFather()->setGender("Homme");
                $errorsFather = $validator->validate($tenant->getFather());
                $errorFather = (count($errorsFather) > 0) ? 1 : 0;

                if($errorFather != 0)
                {
                    $form->addError(new FormError("Père : ".$errorsFather[0]->getMessage()));
                }
            }
            else
            {
                $tenant->setFather(NULL);
            }

            if($dataParent == "2" || $dataParent == "3")
            {
                $tenant->getMother()->setGender("Femme");
                $errorsMother = $validator->validate($tenant->getMother());
                $errorMother = (count($errorsMother) > 0) ? 1 : 0; 

                if($errorMother != 0)
                {
                    $form->addError(new FormError("Mère : ".$errorsMother[0]->getMessage()));
                }
            }
            else
            {
                $tenant->setMother(NULL);
            }

            if($errorMother == 0 && $errorFather == 0)
            {
	           $entityManager->persist($tenant);
	           $entityManager->flush();

	           return $this->redirectToRoute('getTenant', array("id" => $tenant->getId()));
            }
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
    public function modify(Request $request, Tenant $tenant, ValidatorInterface $validator)
    {
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $dataParent = $form->get("parent")->getData();
            $errorFather = 0;
            $errorMother = 0;
            $errorsFather = [];
            $errorsMother = [];

            if($dataParent == "1" || $dataParent == "3")
            {
                $tenant->getFather()->setGender("Homme");
                $errorsFather = $validator->validate($tenant->getFather());
                $errorFather = (count($errorsFather) > 0) ? 1 : 0;

                if($errorFather != 0)
                {
                    $form->addError(new FormError("Père : ".$errorsFather[0]->getMessage()));
                }
            }
            else
            {
                $tenant->setFather(NULL);
            }

            if($dataParent == "2" || $dataParent == "3")
            {
                $tenant->getMother()->setGender("Femme");
                $errorsMother = $validator->validate($tenant->getMother());
                $errorMother = (count($errorsMother) > 0) ? 1 : 0; 

                if($errorMother != 0)
                {
                    $form->addError(new FormError("Mère : ".$errorsMother[0]->getMessage()));
                }
            }
            else
            {
                $tenant->setMother(NULL);
            }

            if($errorMother == 0 && $errorFather == 0)
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('getTenant', array("id" => $tenant->getId()));
            }
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

        //delete associated records
        $repositoryRecord = $this->getDoctrine()->getRepository(Record::class);
        $records = $repositoryRecord->findBy(
            array('tenant' => $tenant)
        );

        foreach ($records as $record) {
            $entityManager->remove($record);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
    
        if($tenant)
        {
            $entityManager->remove($tenant);
            $entityManager->flush();
            
            $response->setContent('Deleted ok');
            $response->setStatusCode(Response::HTTP_OK);
        }
        else
        {
            $response->setContent('Entity not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
 
        return $response;
    }
}
