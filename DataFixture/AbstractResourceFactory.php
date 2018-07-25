<?php

declare(strict_types=1);

namespace Chang\DataFixture;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractResourceFactory implements ExampleFactoryInterface
{
    /**
     * @param OptionsResolver $resolver
     */
    abstract protected function configureOptions(OptionsResolver $resolver): void;

    /**
     * @param string $date
     * @param string $format
     *
     * @return \DateTime
     */
    protected static function createDateTime(string $date, string $format = 'Y-m-d H:i:s'): \DateTime
    {
        return \DateTime::createFromFormat($format, $date);
    }
    /**
     * @param string $date
     * @param string $format
     *
     * @return \DateTime
     */
    protected static function createDate(string $date, string $format = 'Y-m-d'): \DateTime
    {
        return \DateTime::createFromFormat($format, $date);
    }
}
