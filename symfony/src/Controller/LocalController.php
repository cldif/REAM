<?php

namespace App\Controller;

use mikehaertl\pdftk\Pdf;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Local;
use App\Form\LocalType;

use App\Service\SaveFiles;

/**
* @Route("/local")
*/
class LocalController extends AbstractController
{
    /**
     * @Route("/", name="localIndex" , methods={"GET"})
     */
    public function index(\Swift_Mailer $mailer)
    {
    	$repository = $this->getDoctrine()->getRepository(Local::class);
    	$locals= $repository->findAll();


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

	    	$templatePath = $this->getParameter('app.templatePath');
	    	$localPath = $this->getParameter('app.localPath');
	    	$localFolder = $localPath.$local->getId();

            mkdir($localFolder, 0700);
            SaveFiles::saveFiles($form, $localFolder);

	        //Creation of the local document with the template
	        $pdf = new Pdf($templatePath.'localTest.pdf');
			$pdf->fillForm([
			        'nom'=>'mon nom',
			        'prenom' => 'mon prenom',
			        "meuble" => "No",
			    ])
			    ->needAppearances()
			    ->saveAs($localFolder.'/localInscription.pdf');

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

    /**
     * @Route("/{id}/modify", name="modifyLocal", methods={"GET", "POST"})
     */
    public function modify(Request $request, Local $local)
    {
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $localPath = $this->getParameter('app.localPath');
            $localFolder = $localPath.$local->getId();

            SaveFiles::saveFiles($form, $localFolder);

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

    	$localPath = $this->getParameter('app.localPath');
    	$localFolder = $localPath.$local->getId();

		SaveFiles::deleteFolder($localFolder);

        $response = new Response();
		$response->headers->set('Content-Type', 'text/plain');
    
        if($local)
        {
            $entityManager->remove($local);
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
