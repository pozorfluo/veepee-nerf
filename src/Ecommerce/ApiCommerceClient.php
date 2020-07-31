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


    /**
     * Given order must be validated before using mapOrder.
     */
    private function map(OrderInfo $order): string
    {
        // $data = $order->getData();
        // dump((array)$order);
        // dump(json_encode($data));
        // dd($data);

        $client = $order->getClient();
        $addresses = $client->getAddresses();
        $billing = &$addresses[0];
        $shipping = &$addresses[1];

        return json_encode([
            'order' => [
                'id' => $order->getId(),
                'product' => $order->getProduct()->getSku(),
                'payment_method' => $order->getPaymentMethod(),
                'status' => $order->getStatus(),
                'client' => [
                    'firstname' => $billing->getFirstname(),
                    'lastname' => $billing->getLastName(),
                    'email' => $client ->getEmail(),
                ],
                'addresses' => [
                    'billing' => [
                        'address_line1' =>  $billing->getAddress(),
                        'address_line2' => $billing->getaddressComplement(),
                        'city' => $billing->getCity(),
                        'zipcode' => $billing->getZipCode(),
                        'country' => $billing->getCountry()->getName(),
                        'phone' => $billing->getPhone(),
                    ],
                    'shipping' => [
                        'address_line1' => $shipping->getAddress(),
                        'address_line2' => $shipping->getaddressComplement(),
                        'city' => $shipping->getCity(),
                        'zipcode' => $shipping->getZipCode(),
                        'country' => $shipping->getCountry()->getName(),
                        'phone' => $shipping->getPhone(),
                    ],
                ]
            ]
        ]);
    }
    /**
     * @return int API Commerce order_id
     */
    public function createOrder(OrderInfo $order): int
    {
        $payload = $this->map($order);
        dd($payload);

        $url = 'https://api-commerce.simplon-roanne.com/order';

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
