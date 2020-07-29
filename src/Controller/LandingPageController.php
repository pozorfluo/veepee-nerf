<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Address;
use App\Entity\OrderInfo;
use App\Form\ClientType;
use App\Form\AddressType;
use App\Form\OrderInfoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $billing = new Address();
        $billing->setType('billing');

        $delivery = new Address();
        $delivery->setType('delivery');
        
        $client = new Client();
        $client->addAddress($billing);
        $client->addAddress($delivery);

        $orderInfo = new OrderInfo();
        $orderInfo->setStatus('Waiting')
            ->setClient($client);

        $form = $this->createForm(OrderInfoType::class, $orderInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($orderInfo);
            $entityManager->flush();

            // return new Response('submitted, valid.');
            return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }
}
