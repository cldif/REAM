<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Record;
use App\Form\RecordType;

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
    public function add(Request $request)
    {
	    $record = new Record();
	    $form = $this->createForm(RecordType::class, $record);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {

	        $record = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($record);
	        $entityManager->flush();

	    	$recordPath = $this->getParameter('app.recordPath');
            $recordFolder = $recordPath.$record->getId();

	        mkdir($recordFolder, 0700);
            SaveFiles::saveFiles($form, $recordFolder);

	        return $this->redirectToRoute('getRecord', array("id" => $record->getId()));
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

        SaveFiles::deleteFolder($recordFolder);
    
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
