<?php

declare(strict_types=1);

namespace Chang\Twig;

class HttpRedirectExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('chang_http_redirect', function (string $url, int $status = null) {
                $status = $status ?: 302;
                ob_get_clean();
                header("HTTP/1.1 $status Moved Permanently");
                header("Location: $url");
                exit();
            })
        ];
    }
}
