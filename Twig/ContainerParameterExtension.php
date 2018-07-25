<?php

declare(strict_types=1);

namespace Chang\Twig;

use Adbar\Dot;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContainerParameterExtension extends \Twig_Extension
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('parameter', [$this, 'getParameter']),
        ];
    }

    /**
     * @param string $path
     * @param mixed $default
     *
     * @return mixed
     */
    public function getParameter(string $path, $default = null)
    {
        if ($this->parameterBag->has($path)) {
            return $this->parameterBag->get($path) ?? $default;
        }

        $paths = explode('.', $path);

        if ($this->parameterBag->has($paths[0])) {
            $value = $this->parameterBag->get($paths[0]);

            if (is_array($value)) {
                return (new Dot($value))->get($path, $default);
            }
        }

        return $default;
    }
}
