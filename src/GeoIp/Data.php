<?php

declare(strict_types=1);

namespace Chang\GeoIp;

class Data
{
    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $zip;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return null|string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param null|string $countryCode
     */
    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param null|string $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }
}
