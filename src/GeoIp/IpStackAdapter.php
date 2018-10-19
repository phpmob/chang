<?php

declare(strict_types=1);

namespace Chang\GeoIp;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Exception as HttpException;

class IpStackAdapter implements AdapterInterface
{
    private $accessKey;

    public function __construct(string $accessKey = null)
    {
        $this->accessKey = $accessKey;
    }

    /**
     * {@inheritdoc}
     */
    public function lookup(string $ip): Data
    {
        $result = new Data();

        if (empty($ip)) {
            $result->setSuccess(false);

            return $result;
        }

        $client = HttpClientDiscovery::find();;
        $messageFactory = MessageFactoryDiscovery::find();
        $uri = 'http://api.ipstack.com/' . $ip . ($this->accessKey ? '?access_key=' . $this->accessKey : '');

        try {
            $response = $client->sendRequest(
                $messageFactory->createRequest('GET', $uri)
            );

            $data = json_decode(strval($response->getBody()), true);

            $result->setCity($data['city']);
            $result->setZip($data['zip']);
            $result->setCountry($data['country_name']);
            $result->setCountryCode($data['country_code']);

            $result->setSuccess(true);
        } catch (HttpException | \Exception $e) {
            $result->setSuccess(false);
        }

        return $result;
    }
}
