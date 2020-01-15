<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Payment;
use App\Form\PaymentType;

/**
* @Route("/payment")
*/
class PaymentController extends AbstractController
{
    /**
     * @Route("/", name="paymentIndex", methods={"GET"})
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(Payment::class);
    	$payments = $repository->findAll();

        return $this->render('payment/index.html.twig', [
           'payments' => $payments,
        ]);
    }

    /**
	* @Route("/add", name="addPayment", methods={"GET", "POST"})
	*/
    public function add(Request $request)
    {
	    $payment = new Payment();
	    $form = $this->createForm(PaymentType::class, $payment);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {

	        $payment = $form->getData();

	        $entityManager = $this->getDoctrine()->getManager();

	        $entityManager->persist($payment);
	        $entityManager->flush();

	        return $this->redirectToRoute('getPayment', array("id" => $payment->getId()));
	    }

        return $this->render('payment/formAddPayment.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

    /**
	* @Route("/{id}", name="getPayment", methods={"GET"}),
	requirements={"id" = "\d+"))
	*/
    public function get($id)
    {
    	$repository = $this->getDoctrine()->getRepository(Payment::class);
    	$payment = $repository->find($id);

	    if (!$payment) {
	        throw $this->createNotFoundException(
	            'No payment found for id '.$id
	        );
	    }

        return $this->render('payment/getPayment.html.twig', array("payment"  => $payment));
    }

    /**
	* @Route("/record/{idRecord}", name="getAllPayment", methods={"GET"}),
	requirements={"idRecord" = "\d+"))
	*/
    public function getAllPayment($idRecord)
    {
    	$repository = $this->getDoctrine()->getRepository(Payment::class);
    	$payments = $repository->findBy(array('record' => $idRecord));

	    if (!$payments) {
	        throw $this->createNotFoundException(
	            'No payment found for record id '.$idRecord
	        );
	    }

        return $this->render('payment/getAllPayment.html.twig', array("idRecord" => $idRecord, "payments"  => $payments));
    }

    /**
     * @Route("/{id}/modify", name="modifyPayment", methods={"GET", "POST"})
     */
    public function modify(Request $request, Payment $payment)
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('getPayment', array("id" => $payment->getId()));
        }

        return $this->render('payment/formAddPayment.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
