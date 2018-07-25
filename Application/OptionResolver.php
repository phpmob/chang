<?php

declare(strict_types=1);

namespace Chang\Application;

use Adbar\Dot;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OptionResolver
{
    /**
     * @var ParameterBagInterface
     */
    private $parameter;

    /**
     * @var array
     */
    private $cached = [];

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameter = $parameterBag;
    }

    /**
     * @param string $package
     * @param string $feature
     * @param string $option
     *
     * @return mixed|null
     */
    public function get(string $package, string $feature = null, string $option = null)
    {
        if (empty($feature) && empty($option)) {
            list($package, $feature, $option) = explode('.', $package);
        }

        $parameters = [];
        $parameterName = self::makeParameterName($package, $feature);

        if (array_key_exists($parameterName, $this->cached)) {
            $parameters = $this->cached[$parameterName];
        } else {
            if ($this->parameter->has($parameterName)) {
                $this->cached[$parameterName] = $parameters = (array)$this->parameter->get($parameterName);
            }
        }

        return (new Dot($parameters))->get($option, null);
    }

    /**
     * @param string $package
     * @param string $feature
     *
     * @return string
     */
    public static function makeParameterName(string $package, string $feature): string
    {
        $packageName = self::underscore($package);
        $featureName = self::underscore($feature);

        return "chang.packages.$packageName.$featureName";
    }

    /**
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     *
     * @return string The camelized string
     */
    public static function camelize($id)
    {
        return strtr(ucwords(strtr($id, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
    }

    /**
     * @param $id
     *
     * @return string
     */
    public static function underscore($id): string
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), $id));
    }
}
