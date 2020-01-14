<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Record;
use App\Entity\Tenant;

use App\Form\RecordType;

use App\Service\FileManager;

use Symfony\Component\Form\FormError;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
* @Route("/dossier")
*/
class RecordController extends AbstractController
{
    /**
     * @Route("/", name="recordIndex", methods={"GET"})
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(Record::class);
    	$records = $repository->findAll();

        return $this->render('record/index.html.twig', [
            'records' => $records,
        ]);
    }

    /**
	* @Route("/add", name="addRecord", methods={"GET", "POST"})
	*/
    public function add(Request $request, ParameterBagInterface $params, ValidatorInterface $validator)
    {
	    $record = new Record();
	    $form = $this->createForm(RecordType::class, $record);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) 
        {
	        $record = $form->getData();
            $dataGarant = $form->get("garantChoice")->getData();

	        $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Tenant::class);
            $tenant = $repository->find($record->getTenant()->getId());

            //No error
            $error = 0;

            if($dataGarant == 1)
            {
                if($tenant->getFather() == NULL)
                {
                    $error = 1;
                    $form->addError(new FormError("Le père ne peut pas être le garant"));
                }
                else
                {
                    $record->setGarant($tenant->getFather());
                }
            }
            else if($dataGarant == 2)
            {
                if($tenant->getMother() == NULL)
                {
                    $error = 1;
                    $form->addError(new FormError("La mère ne peut pas être la garante"));
                }
                else
                {
                    $record->setGarant($tenant->getMother());
                }
            }
            else if($dataGarant == 3)
            {
                $errorsGarant = $validator->validate($record->getGarant());
                $errorGarant = (count($errorsGarant) > 0) ? 1 : 0;

                if($errorGarant > 0)
                {
                    $error = 1;
                    $form->addError(new FormError("Garant : ".$errorsGarant[0]->getMessage()));
                }
            }

            if($error == 0)
            {
                $entityManager->persist($record);
                $entityManager->flush();

                $recordPath = $this->getParameter('app.recordPath');
                $recordFolder = $recordPath.$record->getId();

                FileManager::verificationStructure($params);
                FileManager::createFolder($recordFolder);

                return $this->redirectToRoute('getRecord', array("id" => $record->getId()));
            }
	    }

        return $this->render('record/formAddRecord.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getRecord", methods={"GET"}),
	requirements={"id" = "\d+"}))
	*/
    public function get($id)
    {
    	$repository = $this->getDoctrine()->getRepository(Record::class);
    	$record = $repository->find($id);

	   if (!$record) {
	        throw $this->createNotFoundException(
	            'No record found for id '.$id
	        );
	    }

	    $recordPath = $this->getParameter('app.recordPath');

	    //Get the associated filed
	    $files = array_diff(scandir($recordPath.$record->getId()), array('..', '.'));

        return $this->render('record/getrecord.html.twig', array("record"  => $record, "files" => $files));
    }

    /**
	* @Route("/{id}", name="deleteRecord", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Record::class);
        $record = $repository->find($id);

        $recordPath = $this->getParameter('app.recordPath');
        $recordFolder = $recordPath.$record->getId();

        FileManager::deleteFolder($recordFolder);
    
        if($record)
        {
            $entityManager->remove($record);
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
