<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Address;
use App\Entity\OrderInfo;
use App\Form\OrderInfoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    /**
     * @Route("/", name="landing_page")
     */
    public function index(Request $request) : Response
    {
        $orderInfo = (new OrderInfo())
            ->setStatus('Waiting')
            ->setClient(
                (new Client())
                    ->addAddress((new Address())->setType('billing'))
                    ->addAddress((new Address())->setType('delivery'))
            );

        $form = $this->createForm(OrderInfoType::class, $orderInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $orderInfo->setTotal($orderInfo->getProduct()->getPrice())
                ->setPaymentMethod(
                    $form->get('paypal')->isClicked()
                        ? 'paypal'
                        : 'stripe'
                );

            $entityManager->persist($orderInfo);
            $entityManager->flush();

            return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation() : Response
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }
}
