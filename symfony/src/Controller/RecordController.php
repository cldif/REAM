<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Record;
use App\Entity\Tenant;

use App\Form\RecordType;

use App\Service\FileManager;

use Symfony\Component\Form\FormError;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
* @Route("/record")
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
            $dataGuarantor = $form->get("guarantorChoice")->getData();

	        $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Tenant::class);
            $tenant = $repository->find($record->getTenant()->getId());

            $error = 0;

            if($dataGuarantor == 1)
            {
                if($tenant->getFather() == NULL)
                {
                    $error = 1;
                    $form->addError(new FormError("Le père ne peut pas être le garant"));
                }
                else
                {
                    $record->setGuarantor($tenant->getFather());
                }
            }
            else if($dataGuarantor == 2)
            {
                if($tenant->getMother() == NULL)
                {
                    $error = 1;
                    $form->addError(new FormError("La mère ne peut pas être la garante"));
                }
                else
                {
                    $record->setGuarantor($tenant->getMother());
                }
            }
            else if($dataGuarantor == 3)
            {
                $errorsGuarantor = $validator->validate($record->getGuarantor());
                $errorGuarantor = (count($errorsGuarantor) > 0) ? 1 : 0;

                if($errorGuarantor > 0)
                {
                    $error = 1;
                    $form->addError(new FormError("Garant : ".$errorsGuarantor[0]->getMessage()));
                }
            }
            else
            {
                $error = 1;
                $form->addError(new FormError("Ce choix de garant n'est pas possible"));
            }

            if($error == 0)
            {
                $entityManager->persist($record);
                $entityManager->flush();

                $recordPath = $this->getParameter('app.recordPath');
                $recordFolder = $recordPath.$record->getId()."/";
                $roomTemplatePath = $this->getParameter('app.roomTemplatesPath');
                $roomFolder = $roomTemplatePath.$record->getRoom()->getId()."/";
                $generalTemplatePath = $this->getParameter('app.generalTemplatesPath');

                if($record->getRoom()->getType() === "Hangar")
                {
                	$typedTemplatePath = $this->getParameter('app.hangarsTemplatesPath');
                }
                else
                {
					$typedTemplatePath = $this->getParameter('app.apartmentsTemplatesPath');
                }


                FileManager::verificationStructure($params);
                FileManager::createFolder($recordFolder);

                // Code to fill room template
                //$checked = '☑'; 
                //$unChecked = '☐';
                $keys = array('/locataire/nom', 
                              '/locataire/prenom',
                              '/locataire/adresse',
                              '/locataire/dateNaissance',
                              '/locataire/lieuNaissance',
                              '/locataire/telFixe',
                              '/locataire/telMobile',
                              '/room/nom',
                              '/room/surface',
                              '/record/dateContrat',
                              '/record/duree',
                              '/record/loyer',
                              '/record/charge',
                              'jour'
                            );

                $values = array($record->getTenant()->getName(), 
                                $record->getTenant()->getFirstName(), 
                                $record->getTenant()->getAddress(),
                                strftime("%e %B %G", $record->getTenant()->getDateOfBirth()->getTimestamp()),
                                $record->getTenant()->getBirthPlace(),
                                $record->getTenant()->getPhone(),
                                $record->getTenant()->getMobilePhone(),
                                $record->getRoom()->getName(),
                                $record->getRoom()->getSurface(),
                                strftime("%e %B %G", $record->getEntryDate()->getTimestamp()),
                                (date_diff($record->getReleaseDate(), $record->getEntryDate())->format('%Y années %M mois')),
                                $record->getRent(),
                                $record->getFixedCharge(),
                                date('d m Y')
                            );

            	FileManager::fillAllTemplates($roomFolder, $recordFolder, $keys, $values);
            	FileManager::fillAllTemplates($generalTemplatePath, $recordFolder, $keys, $values);
            	FileManager::fillAllTemplates($typedTemplatePath, $recordFolder, $keys, $values);

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
        $files = FileManager::getFilesInFolder($recordPath.$record->getId());

        return $this->render('record/getRecord.html.twig', array("record"  => $record, "files" => $files));
    }

    /**
	* @Route("/{id}", name="deleteRecord", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Record::class);
        $record = $repository->find($id);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if (!$record) 
        {
            $response->setContent('Entity not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        else
        {
            $recordPath = $this->getParameter('app.recordPath');
            $recordFolder = $recordPath.$record->getId();
            
            FileManager::deleteFolder($recordFolder);

            $entityManager->remove($record);
            $entityManager->flush();
            
            $response->setContent('Deleted ok');
            $response->setStatusCode(Response::HTTP_OK);
        }    
 
        return $response;
    }

    /**
    * @Route("/{id}/document", name="getDocumentRecord", methods={"GET"}, requirements={"id" = "\d+"})
    */
    public function getDocument($id, Request $request)
    {
        $recordPath = $this->getParameter('app.recordPath');
        $recordFolder = $recordPath.$id;

        return FileManager::getDocument($recordFolder, $request);
    }

    /**
    * @Route("/{id}/allDocument", name="getAllDocumentRecord", methods={"GET"}, requirements={"id" = "\d+"})
    */
    public function getAllDocument($id)
    {
        $recordPath = $this->getParameter('app.recordPath');
        $recordFolder = $recordPath.$id;

        return FileManager::getALlDocument($recordFolder);
    }
}
