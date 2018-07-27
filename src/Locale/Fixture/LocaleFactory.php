<?php

declare(strict_types=1);

namespace Chang\Locale\Fixture;

use Chang\DataFixture\AbstractResourceFactory;
use Chang\DataFixture\ExampleFactoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleFactory extends AbstractResourceFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $localeFactory;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(FactoryInterface $localeFactory, string $defaultLocale)
    {
        $this->localeFactory = $localeFactory;
        $this->defaultLocale = $defaultLocale;
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var LocaleInterface $locale */
        $locale = $this->localeFactory->createNew();
        $locale->setCode($options['code']);

        return $locale;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', $this->defaultLocale)
        ;
    }
}
