<?php

declare(strict_types=1);

namespace Chang\GeoIp;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;

class GeoIP2DBAdapter implements AdapterInterface
{
    private $resource;

    public function __construct(string $resource = null)
    {
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function lookup(string $ip): Data
    {
        $result = new Data();

        if (!$this->resource || empty($ip)) {
            $result->setSuccess(false);

            return $result;
        }

        try {
            $reader = new Reader($this->resource);
            $data = $reader->city($ip);
            $reader->close();

            $result->setCity($data->city->name);
            $result->setZip($data->postal->code);
            $result->setCountry($data->country->name);
            $result->setCountryCode($data->country->isoCode);

        } catch (InvalidDatabaseException | AddressNotFoundException $e) {
            $result->setSuccess(false);
        }

        return $result;
    }
}
