<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\DeliveryAddress;
use App\Entity\OrderInfo;
use App\Form\ClientType;
use App\Form\DeliveryAddressType;
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
        $client = new Client();
        $client->setDeliveryAddress(new DeliveryAddress());

        $orderInfo = new OrderInfo();
        $orderInfo->setStatus('Waiting')
            ->setClient($client);

        $form = $this->createForm(OrderInfoType::class, $orderInfo);

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
