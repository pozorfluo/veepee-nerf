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
        //Your code here

        return $this->render('landing_page/index.html.twig', []);
    }
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }


    /**
     * @Route("/formtest", name="form_test")
     */
    public function formTest(): Response
    {
        $formData = [
            'client' => new Client(),
            'delivery' => new DeliveryAddress(),
            'order' => new OrderInfo()
        ];

        $forms = [
            'client' => $this->createForm(
                ClientType::class,
                $formData['client']
            ),
            'delivery' => $this->createForm(
                DeliveryAddressType::class,
                $formData['delivery']
            ),
            'order' => $this->createForm(
                OrderInfoType::class,
                $formData['order']
            ),
        ];

        return $this->render('landing_page/form_test.html.twig', [
            'form_client' => $forms['client']->createView(),
            'form_delivery' => $forms['delivery']->createView(),
            'form_order' => $forms['order']->createView(),
        ]);
    }
}
