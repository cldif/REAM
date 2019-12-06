<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Local;
use App\Form\LocalType;

/**
* @Route("/local")
*/
class LocalController extends AbstractController
{
    /**
     * @Route("/", name="localIndex")
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(Local::class);
    	$locals= $repository->findAll();

        return $this->render('local/index.html.twig', [
            'locals' => $locals,
        ]);
    }

	/**
	* @Route("/add", name="addLocal", methods={"GET", "POST"})
	*/
    public function add(Request $request)
    {
	    $local = new Local();
	    $form = $this->createForm(LocalType::class, $local);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {

	        $local = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($local);
	        $entityManager->flush();

	    	$localPath = $this->getParameter('app.localPath');

	        //local creation and move the files in it
	        mkdir($localPath.$local->getId(), 0777);
	        $files = $form['files']->getData();
	        foreach ($files as $file) {
        		$file->move($localPath.$local->getId(), $file->getClientOriginalName());
	        }

	        return $this->redirectToRoute('getLocal', array("id" => $local->getId()));
	    }

        return $this->render('local/formAddLocal.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getLocal", methods={"GET"}),
	requirements={"id" = "\d+"}))
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

	    $localPath = $this->getParameter('app.localPath');

	    //Get the associated filed
	    $files = array_diff(scandir($localPath.$local->getId()), array('..', '.'));

        return $this->render('local/getLocal.html.twig', array("local"  => $local, "files" => $files));
    }
}
