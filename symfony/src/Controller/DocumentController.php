<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use App\Service\FileManager;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
* @Route("/document")
*/
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="getDocument" , methods={"GET"})
     */
    public function getDocument(Request $request)
    {
    	$documentName = $request->headers->get("documentName");
        $generalDocumentPath = $this->getParameter('app.generalTemplatesPath');

    	if(!$documentName)
    	{
    		$documents = FileManager::getFilesInFolder($generalDocumentPath);

	        return $this->render('document/index.html.twig', [
	            'documents' => $documents,
	        ]);
    	}
    	else
    	{
	        return new BinaryFileResponse($generalDocumentPath."/".$documentName);
    	}
    }

    /**
    * @Route("/", name="addDocument", methods={"POST"})
    */
    public function addDocument(Request $request, ParameterBagInterface $params)
    {
        $generalDocumentPath = $this->getParameter('app.generalTemplatesPath');
        FileManager::verificationStructure($params);
        return FileManager::addDocument($generalDocumentPath, $request);
    }

    /**
    * @Route("/", name="removeDocument", methods={"DELETE"})
    */
    public function removeDocument(Request $request)
    {
        $generalDocumentPath = $this->getParameter('app.generalTemplatesPath');
        return FileManager::removeDocument($generalDocumentPath, $request);
    }

    /**
     * @Route("/apartment", name="getDocumentApartment" , methods={"GET"})
     */
    public function getDocumentApartment(Request $request)
    {
    	$documentName = $request->headers->get("documentName");
        $apartmentsTemplatesPath = $this->getParameter('app.apartmentsTemplatesPath');

    	if(!$documentName)
    	{
    		$documents = FileManager::getFilesInFolder($apartmentsTemplatesPath);

	        return $this->render('document/index.html.twig', [
	            'documents' => $documents,
	        ]);
    	}
    	else
    	{
	        return new BinaryFileResponse($apartmentsTemplatesPath."/".$documentName);
    	}
    }

    /**
    * @Route("/apartment", name="addDocumentApartment", methods={"POST"})
    */
    public function addDocumentApartment(Request $request, ParameterBagInterface $params)
    {
        $apartmentsTemplatesPath = $this->getParameter('app.apartmentsTemplatesPath');
        FileManager::verificationStructure($params);
        return FileManager::addDocument($apartmentsTemplatesPath, $request);
    }

    /**
    * @Route("/apartment", name="removeDocumentApartment", methods={"DELETE"})
    */
    public function removeDocumentApartment(Request $request)
    {
        $apartmentsTemplatesPath = $this->getParameter('app.apartmentsTemplatesPath');
        return FileManager::removeDocument($apartmentsTemplatesPath, $request);
    }

    /**
     * @Route("/hangar", name="getDocumentHangar" , methods={"GET"})
     */
    public function getDocumentHangar(Request $request)
    {
    	$documentName = $request->headers->get("documentName");
        $hangarsTemplatesPath = $this->getParameter('app.hangarsTemplatesPath');

    	if(!$documentName)
    	{
    		$documents = FileManager::getFilesInFolder($hangarsTemplatesPath);

	        return $this->render('document/index.html.twig', [
	            'documents' => $documents,
	        ]);
    	}
    	else
    	{
	        return new BinaryFileResponse($hangarsTemplatesPath."/".$documentName);
    	}
    }

    /**
    * @Route("/hangar", name="addDocumentHangar", methods={"POST"})
    */
    public function addDocumentHangar(Request $request, ParameterBagInterface $params)
    {
        $hangarsTemplatesPath = $this->getParameter('app.hangarsTemplatesPath');
        FileManager::verificationStructure($params);
        return FileManager::addDocument($hangarsTemplatesPath, $request);
    }

    /**
    * @Route("/hangar", name="removeDocumentHangar", methods={"DELETE"})
    */
    public function removeDocumentHangar(Request $request)
    {
        $hangarsTemplatesPath = $this->getParameter('app.hangarsTemplatesPath');
        return FileManager::removeDocument($hangarsTemplatesPath, $request);
    }
}
