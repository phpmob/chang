<?php

declare(strict_types=1);

namespace Chang\GeoIp;

interface AdapterInterface
{
    /**
     * @param string $ip
     *
     * @return Data
     */
    public function lookup(string $ip): Data;
}
