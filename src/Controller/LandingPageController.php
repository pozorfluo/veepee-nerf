<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
}
