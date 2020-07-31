<?php

namespace App\Controller;

use App\Ecommerce\ApiCommerceClient;
use App\Entity\Client;
use App\Entity\Address;
use App\Entity\OrderInfo;
use App\Form\OrderInfoType;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    /**
     * @Route("/", name="landing_page")
     */
    public function index(
        Request $request,
        ApiCommerceClient $apiClient
    ): Response {

        $order = (new OrderInfo())
            ->setStatus('WAITING')
            ->setClient(
                (new Client())
                    ->addAddress((new Address())->setType('billing'))
                    ->addAddress((new Address())->setType('delivery'))
            );

        $form = $this->createForm(OrderInfoType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $order->setTotal($order->getProduct()->getPrice())
                ->setPaymentMethod(
                    $form->get('paypal')->isClicked()
                        ? 'paypal'
                        : 'stripe'
                );

            try {
                $order->setApiOrderId($apiClient->createOrder($order) ?? -1);
            } catch (ClientException $e) {
                dump($e);
                return $this->render('landing_page/index.html.twig', [
                    'form' => $form->createView(),
                    'api_error' => 'api_create_order_failed'
                ]);
            }

            $product = $order->getProduct();
            $product->setInventory($product->getInventory() - 1);

            $entityManager->persist($order);
            $entityManager->flush();

            return $this->payWithStripe($order);
        }

        return $this->render('landing_page/index.html.twig', [
            'form' => $form->createView(),
            'api_error' => $request->get("api_error") ?? "",
        ]);
    }

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(): Response
    {
        return $this->render('landing_page/confirmation.html.twig', []);
    }

    private function payWithStripe(OrderInfo $order): Response
    {
        try {
            $stripe = new \Stripe\StripeClient(
                $this->getParameter('app.stripe_key')
            );

            $product = $order->getProduct();

            $intent = $stripe->paymentIntents->create([
                'amount' => $order->getTotal(),
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'receipt_email' => $order->getClient()->getEmail(),
                'description' => $product->getSku() . ' : ' . $product->getName(),
                'metadata' => ['sku' =>  $product->getSku()],
            ]);

            return $this->render('payment/index.html.twig', [
                'intent' => $intent,
            ]);
        } catch (Error $e) {
            return $this->redirectToRoute("landing_page", [
                'api_error' => 'stripe_create_intent_failed'
            ]);
        }
    }

    //     /**
    //      * @Route("/api_validation/{id}", name="api_validation_test")
    //      */
    //     public function testApiValidation(
    //         Request $request,
    //         HttpClientInterface $httpClient,
    //         string $id
    //     ): Response {
    //         $url = 'https://api-commerce.simplon-roanne.com/order/' . $id . '/status';
    //         $payload = <<<JSON
    //         {
    //             "status": "PAID"
    //         }
    // JSON;
    //         $api_error = $request->get("api_error") ?? "";

    //         if (!$api_error) {
    //             try {
    //                 $response = $httpClient->request('POST', $url, [
    //                     'headers' => [
    //                         'Accept' => 'application/json',
    //                         'Authorization' => 'Bearer mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX',
    //                         'Content-Type' => 'application/json',
    //                         'User-Agent' => 'veepee-nerf'
    //                     ],
    //                     'body' => $payload,
    //                     'timeout' => 10
    //                 ]);
    //                 dump($url);
    //                 dump($response->getStatusCode());
    //                 dd($response->toArray());
    //             } catch (ClientException $e) {
    //                 dump($e);
    //                 return $this->redirectToRoute("api_test", [
    //                     'api_error' => 'api_request_status_failed'
    //                 ]);
    //             }
    //             return $this->redirectToRoute("confirmation");
    //         }
    //         return $this->render('test.html.twig', [
    //             'api_error' => $api_error,
    //             'order_id' => $id,
    //         ]);
    //     }
}
