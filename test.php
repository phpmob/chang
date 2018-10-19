<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

$api = new \Chang\GeoIp\IpStackAdapter('82cb72d46f794e3e2b230baf04c7fc2e');
//$api = new \Chang\GeoIp\GeoIP2DBAdapter('/Users/dos/Downloads/GeoLite2-City_20181016/GeoLite2-City.mmdb');

var_dump($api->lookup('183.88.76.126'));
