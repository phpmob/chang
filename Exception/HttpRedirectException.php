<?php

declare(strict_types=1);

namespace Chang\Exception;

use Throwable;

class HttpRedirectException extends \Exception
{
    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var int
     */
    private $status;

    public function __construct(string $message = "", int $code = 302, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->targetUrl = $message;
        $this->status = $code;
    }

    /**
     * @param string $targetUrl
     * @param int $status
     *
     * @return HttpRedirectException
     */
    public static function create(string $targetUrl, int $status = 302): self
    {
        return new self($targetUrl, $status);
    }

    /**
     * @return string
     */
    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    /**
     * @param string $targetUrl
     */
    public function setTargetUrl(string $targetUrl): void
    {
        $this->targetUrl = $targetUrl;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
