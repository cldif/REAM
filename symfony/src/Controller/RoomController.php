<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Room;
use App\Entity\Record;
use App\Form\RoomType;

use App\Service\FileManager;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
* @Route("/room")
*/
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="roomIndex" , methods={"GET"})
     */
    public function index(\Swift_Mailer $mailer)
    {
    	$repository = $this->getDoctrine()->getRepository(Room::class);
    	$rooms = $repository->findAll();


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

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

	/**
	* @Route("/add", name="addRoom", methods={"GET", "POST"})
	*/
    public function add(Request $request, ParameterBagInterface $params)
    {
	    $room = new Room();
	    $form = $this->createForm(RoomType::class, $room);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) 
        {
	        $room = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($room);
	        $entityManager->flush();

            $roomPath = $this->getParameter('app.roomTemplatesPath');
            $roomFolder = $roomPath.$room->getId();

            FileManager::verificationStructure($params);
            FileManager::createFolder($roomFolder);

	        return $this->redirectToRoute('getRoom', array("id" => $room->getId()));
	    }

        return $this->render('room/formAddRoom.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getRoom", methods={"GET"},	requirements={"id" = "\d+"})
	*/
    public function get($id)
    {
    	$repository = $this->getDoctrine()->getRepository(Room::class);
    	$room = $repository->find($id);

	   if (!$room) {
	        throw $this->createNotFoundException(
	            'No room found for id '.$id
	        );
	    }

	    $roomPath = $this->getParameter('app.roomTemplatesPath');
	    $files = FileManager::getFilesInFolder($roomPath.$room->getId());

        return $this->render('room/getRoom.html.twig', array("room"  => $room, "files" => $files));
    }

    /**
     * @Route("/{id}/modify", name="modifyRoom", methods={"GET", "POST"},  requirements={"id" = "\d+"})
     */
    public function modify(Request $request, Room $room)
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('getRoom', array("id" => $room->getId()));
        }

        return $this->render('room/formAddRoom.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
	* @Route("/{id}", name="deleteRoom", methods={"DELETE"}, requirements={"id" = "\d+"})
	*/
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Room::class);
        $room = $repository->find($id);

        //delete associated records
        $repositoryRecord = $this->getDoctrine()->getRepository(Record::class);
        $records = $repositoryRecord->findBy(
            array('room' => $room)
        );

        foreach ($records as $record) {
            $entityManager->remove($record);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        if (!$room) 
        {
            $response->setContent('Entity not found');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        else
        {
            $roomPath = $this->getParameter('app.roomTemplatesPath');
            $roomFolder = $roomPath.$room->getId();
            FileManager::deleteFolder($roomFolder);

            $entityManager->remove($room);
            $entityManager->flush();
            
            $response->setContent('Deleted ok');
            $response->setStatusCode(Response::HTTP_OK);
        }    
 
        return $response;
    }

    /**
    * @Route("/{id}/document", name="getDocumentRoom", methods={"GET"}, requirements={"id" = "\d+"})
    */
    public function getDocument($id, Request $request)
    {
        $roomPath = $this->getParameter('app.roomTemplatesPath');
        $roomFolder = $roomPath.$id;

        return FileManager::getDocument($roomFolder, $request);
    }

    /**
    * @Route("/{id}/document", name="addDocumentRoom", methods={"POST"}, requirements={"id" = "\d+"})
    */
    public function addDocument($id, Request $request, ParameterBagInterface $params)
    {
        $roomPath = $this->getParameter('app.roomTemplatesPath');
        $roomFolder = $roomPath.$id;

        FileManager::verificationStructure($params);
        $extensionsAllowed = ["docx"];
        return FileManager::addDocument($roomFolder, $request, $extensionsAllowed);
    }

    /**
    * @Route("/{id}/document", name="removeDocumentRoom", methods={"DELETE"}, requirements={"id" = "\d+"})
    */
    public function removeDocument($id, Request $request)
    {
        $roomPath = $this->getParameter('app.roomTemplatesPath');
        $roomFolder = $roomPath.$id;

        return FileManager::removeDocument($roomFolder, $request);
    }
}
