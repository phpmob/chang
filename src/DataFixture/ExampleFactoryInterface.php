<?php

declare(strict_types=1);

namespace Chang\DataFixture;

interface ExampleFactoryInterface
{
    /**
     * @param array $options
     *
     * @return object
     */
    public function create(array $options = []);
}
