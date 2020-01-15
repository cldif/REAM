<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Local;
use App\Form\LocalType;

use App\Service\FileManager;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
* @Route("/room")
*/
class LocalController extends AbstractController
{
    /**
     * @Route("/", name="localIndex" , methods={"GET"})
     */
    public function index(\Swift_Mailer $mailer)
    {
    	$repository = $this->getDoctrine()->getRepository(Local::class);
    	$locals = $repository->findAll();


       /*
		Code to fill a docx file and save as pdf

		$checked = '☑'; 
        $unChecked = '☐';
        $keys = array('name', 'prenom', "toto");
        $values = array("toto", "titi", $unChecked);

       FileManager::fillTemplate($keys, $values, "template.docx", "result.pdf");

       */

		/*$message = (new \Swift_Message('Hello Email, this is a test'))
	        ->setFrom('test@wallofnames.com')
	        ->setTo('sylvain.bessonneau@outlook.fr')
	        ->setBody(
	            $this->renderView(
	                // templates/emails/registration.html.twig
	                'emails/test.html.twig',
	                ['name' => "tatatattadelfnkj"]
	            ),
	            'text/html'
	        );

    	$mailer->send($message);*/

        return $this->render('local/index.html.twig', [
            'locals' => $locals,
        ]);
    }

	/**
	* @Route("/add", name="addLocal", methods={"GET", "POST"})
	*/
    public function add(Request $request, ParameterBagInterface $params)
    {
	    $local = new Local();
	    $form = $this->createForm(LocalType::class, $local);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) 
        {
	        $local = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($local);
	        $entityManager->flush();

            $localPath = $this->getParameter('app.roomTemplatesPath');
            $localFolder = $localPath.$local->getId();

            FileManager::verificationStructure($params);
            FileManager::createFolder($localFolder);

	        return $this->redirectToRoute('getLocal', array("id" => $local->getId()));
	    }

        return $this->render('local/formAddLocal.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getLocal", methods={"GET"},	requirements={"id" = "\d+"})
	*/
    public function get($id)
    {
    	$repository = $this->getDoctrine()->getRepository(Local::class);
    	$local = $repository->find($id);

	   if (!$local) {
	        throw $this->createNotFoundException(
	            'No local found for id '.$id
	        );
	    }

	    $localPath = $this->getParameter('app.roomTemplatesPath');
	    $files = FileManager::getFilesInFolder($localPath.$local->getId());

        return $this->render('local/getLocal.html.twig', array("local"  => $local, "files" => $files));
    }

    /**
     * @Route("/{id}/modify", name="modifyLocal", methods={"GET", "POST"},  requirements={"id" = "\d+"})
     */
    public function modify(Request $request, Local $local)
    {
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('getLocal', array("id" => $local->getId()));
        }

        return $this->render('local/formAddLocal.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
	* @Route("/{id}", name="deleteLocal", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Local::class);
        $local = $repository->find($id);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if (!$local) 
        {
            $response->setContent('Entity not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        else
        {
            $localPath = $this->getParameter('app.roomTemplatesPath');
            $localFolder = $localPath.$local->getId();
            FileManager::deleteFolder($localFolder);

            $entityManager->remove($local);
            $entityManager->flush();
            
            $response->setContent('Deleted ok');
            $response->setStatusCode(Response::HTTP_OK);
        }    
 
        return $response;
    }

    /**
    * @Route("/{id}/document", name="getDocumentLocal", methods={"GET"}, requirements={"id" = "\d+"})
    */
    public function getDocument($id, Request $request)
    {
        $documentName = $request->headers->get("documentName");

        $localPath = $this->getParameter('app.roomTemplatesPath');
        $localFolder = $localPath.$id;

        return FileManager::getDocument($localFolder."/".$documentName);
    }

    /**
    * @Route("/{id}/document", name="addDocumentLocal", methods={"POST"}, requirements={"id" = "\d+"})
    */
    public function addDocument($id, Request $request, ParameterBagInterface $params)
    {
        $localPath = $this->getParameter('app.roomTemplatesPath');
        $localFolder = $localPath.$id;

        FileManager::verificationStructure($params);
        return FileManager::addDocument($localFolder, $request);
    }

    /**
    * @Route("/{id}/document", name="removeDocumentLocal", methods={"DELETE"}, requirements={"id" = "\d+"})
    */
    public function removeDocument($id, Request $request)
    {
        $localPath = $this->getParameter('app.roomTemplatesPath');
        $localFolder = $localPath.$id;

        return FileManager::removeDocument($localFolder, $request);
    }
}
