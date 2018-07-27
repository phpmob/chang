<?php

declare(strict_types=1);

namespace Chang\Twig;

use Adbar\Dot;

class ArrayExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('array_get', [$this, 'arrayGet']),
            new \Twig_SimpleFunction('array_set', [$this, 'arraySet']),
            new \Twig_SimpleFunction('array_unset', [$this, 'arrayUnset']),
            new \Twig_SimpleFunction('is_array', 'is_array'),
        ];
    }

    /**
     * @param array $data
     * @param string $path
     * @param mixed $default
     *
     * @return mixed
     */
    public function arrayGet(array $data = [], $path, $default = null)
    {
        return (new Dot($data))->get($path, $default);
    }

    /**
     * @param array $data
     * @param string $path
     * @param mixed $value
     *
     * @return mixed
     */
    public function arraySet(array $data, string $path, $value)
    {
        $dot = new Dot($data);
        $dot->set($path, $value);

        return $dot->all();
    }

    /**
     * @param array $data
     * @param string $path
     *
     * @return mixed
     */
    public function arrayUnset(array $data, string $path)
    {
        $dot = new Dot($data);
        $dot->delete($path);

        return $dot->all();
    }
}
