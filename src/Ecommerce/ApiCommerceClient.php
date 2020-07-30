<?php

namespace App\Ecommerce;

use App\Entity\OrderInfo;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiCommerceClient
{

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var LoggerInterface */
    private $logger;

    private $token;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        $token
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->token = $token;
    }


    private function serializeOrder(OrderInfo $order): string
    {
        $data = $order->getData();
        dump((array)$order);
        dump(json_encode($data));
        dd($data);
        return json_encode([
            'order' => [
                'id' => $data['id'],
                'product' => $data['product']->getSku(),
                'payment_method' => $data['paymentMethod'],
                'status' => 'WAITING',
                // 'client' => [
                //     'firstname' => $data['client']->getFirstname(),
                //     'lastname' => $data['client']->getLastName(),
                //     'email' => $data['client']->getEmail(),
                // ],
                // 'addresses' => [
                //     'billing' => [
                //         'address_line1' => $data[''],
                //         'address_line2' => $data[''],
                //         'city' => $data[''],
                //         'zipcode' => $data[''],
                //         'country' => $data[''],
                //         'phone' => $data[''],
                //     ],
                //     'shipping' => [
                //         'address_line1' => $data[''],
                //         'address_line2' => $data[''],
                //         'city' => $data[''],
                //         'zipcode' => $data[''],
                //         'country' => $data[''],
                //         'phone' => $data[''],
                //     ],
                // ]
            ]
        ]);
    }
    /**
     * @return int API Commerce order_id
     */
    public function createOrder(OrderInfo $order): int
    {
        $payload = $this->serializeOrder($order);
        dump($payload);
        dump($this->token);
        dd($order);

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
        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'User-Agent' => 'veepee-nerf'
            ],
            'body' => $payload,
            'timeout' => 10
        ]);

        dd($response->getContent());
        return $response->toArray()['order_id'];
    }
}
