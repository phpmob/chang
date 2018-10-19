<?php

declare(strict_types=1);

namespace Chang\GeoIp;

interface DataSourceInterface
{
    /**
     * @param string $ip
     *
     * @return Data
     */
    public function getData(string $ip): Data;
}
