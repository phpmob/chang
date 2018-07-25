<?php

declare(strict_types=1);

namespace Chang\DataFixture;

use Chang\Admin\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserFactory extends AbstractResourceFactory
{
    /**
     * @var FactoryInterface
     */
    private $userFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $userFactory
     */
    public function __construct(FactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = []): AdminUserInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var AdminUserInterface $user */
        $user = $this->userFactory->createNew();
        $user->setEmail($options['email']);
        $user->setUsername($options['username']);
        $user->setPlainPassword($options['password']);
        $user->setEnabled($options['enabled']);

        if (isset($options['locale_code'])) {
            $user->setLocaleCode($options['locale_code']);
        }

        if (isset($options['first_name'])) {
            $user->setFirstName($options['first_name']);
        }

        if (isset($options['last_name'])) {
            $user->setLastName($options['last_name']);
        }

        if ($options['roles']) {
            foreach ($options['roles'] as $role) {
                $user->addRole($role);
            }
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('email', function (Options $options): string {
                return $this->faker->email;
            })
            ->setDefault('username', function (Options $options): string {
                return $this->faker->userName;
            })
            ->setDefault('enabled', function (Options $options): bool {
                return $this->faker->boolean;
            })
            ->setDefault('first_name', function (Options $options): string {
                return $this->faker->firstName;
            })
            ->setDefault('last_name', function (Options $options): string {
                return $this->faker->lastName;
            })
            ->setAllowedTypes('enabled', 'bool')
            ->setDefault('password', 'password123')
            ->setDefined('locale_code')
            ->setDefault('roles', ['ROLE_ADMIN'])
            ->setAllowedTypes('roles', 'array')
        ;
    }
}
