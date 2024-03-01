<?php

namespace App\DataFixtures;

use App\Entity\Market;
use GuzzleHttp\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MarketFixtures extends Fixture
{
    public function __construct(private string $API_KEY)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest";

        $response = $client->request('GET', $url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->API_KEY,
                'Accept' => 'application/json',
            ],
            'verify' => false,
        ]);

        $marketData = json_decode($response->getBody());


            foreach ($marketData->data as $data) {
        $crypto = new Market();
        $crypto->setName($data->name);
        $crypto->setCode($data->symbol);
        $crypto->setPrice($data->quote->USD->price);
        $crypto->setMarketCap($data->quote->USD->market_cap);
        $crypto->setMaxSupply($data->max_supply);
        $crypto->setSlug($data->slug . '-' . uniqid());
        $manager->persist($crypto);
    }
        $manager->flush();
    }
}
