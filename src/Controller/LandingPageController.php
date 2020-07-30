<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Address;
use App\Entity\OrderInfo;
use App\Form\OrderInfoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LandingPageController extends AbstractController
{

    /**
     * @Route("/", name="landing_page")
     */
    public function index(Request $request): Response
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
                )
                ->setApiOrderId(0);

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
    public function confirmation(): Response
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }

    /**
     * @Route("/api", name="api_test")
     */
    public function testApi(Request $request, HttpClientInterface $httpClient): Response
    {
        $url = 'https://api-commerce.simplon-roanne.com/order';
        $payload = <<<JSON
        {
            "order": {
                "id": 1,
                "product": "Nerf Elite Jolt",
                "payment_method": "paypal",
                "status": "WAITING",
                "client": {
                "firstname": "Z",
                "lastname": "API CALL TEST",
                "email": "francois.dupont@gmail.com"
                },
                "addresses": {
                "billing": {
                    "address_line1": "1, rue du test",
                    "address_line2": "3ème étage",
                    "city": "Lyon",
                    "zipcode": "69000",
                    "country": "France",
                    "phone": "string"
                },
                "shipping": {
                    "address_line1": "1, rue du test",
                    "address_line2": "3ème étage",
                    "city": "Lyon",
                    "zipcode": "69000",
                    "country": "France",
                    "phone": "string"
                }
                }
            }
        }
JSON;
        $api_error = $request->get("api_error") ?? "";

        if (!$api_error) {
            try {
                $response = $httpClient->request('POST', $url, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'veepee-nerf'
                    ],
                    'body' => $payload,
                    'timeout' => 10
                ]);
                dump($response->getStatusCode());
                return $this->redirectToRoute("api_validation_test", [
                    'id' => $response->toArray()['order_id'],
                ]);
            } catch (ClientException $e) {
                dump($e);
                return $this->redirectToRoute("api_test", [
                    'api_error' => 'api_request_order_failed'
                ]);
            }
        }
        return $this->render('test.html.twig', [
            'api_error' => $api_error,
        ]);
    }
    /**
     * @Route("/api_validation/{id}", name="api_validation_test")
     */
    public function testApiValidation(
        Request $request,
        HttpClientInterface $httpClient,
        string $id
    ): Response {
        $url = 'https://api-commerce.simplon-roanne.com/order/'.$id.'/status';
        $payload = <<<JSON
        {
            "status": "PAID"
        }
JSON;
        $api_error = $request->get("api_error") ?? "";

        if (!$api_error) {
            try {
                $response = $httpClient->request('POST', $url, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'veepee-nerf'
                    ],
                    'body' => $payload,
                    'timeout' => 10
                ]);
                dump($url);
                dump($response->getStatusCode());
                dd($response->toArray());
            } catch (ClientException $e) {
                dump($e);
                return $this->redirectToRoute("api_test", [
                    'api_error' => 'api_request_status_failed'
                ]);
            }
            return $this->redirectToRoute("confirmation");
        }
        return $this->render('test.html.twig', [
            'api_error' => $api_error,
            'order_id' => $id,
        ]);
    }
}
