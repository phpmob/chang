<?php

declare(strict_types=1);

namespace Chang\Twig;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class MergeDeepExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter('merge_deep', [$this, 'mergeDeep']),
        ];
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public function mergeDeep(array $array1, array $array2)
    {
        return array_replace_recursive($array1, $array2);
    }
}
