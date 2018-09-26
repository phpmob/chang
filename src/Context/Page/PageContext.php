<?php

declare(strict_types=1);

namespace Chang\Context\Page;

use Adbar\Dot;
use PhpMob\Settings\Manager\SettingManagerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PageContext implements PageContextInterface
{
    /**
     * @var SettingManagerInterface
     */
    private $settings;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Default context `app` will be used in none firewall app eg. `Console Command`.
     *
     * @var string
     */
    private $context = 'app';

    /**
     * @var array
     */
    private $parameters = [];

    public function __construct(
        ParameterBagInterface $parameterBag,
        SettingManagerInterface $settings,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    )
    {
        $this->parameterBag = $parameterBag;
        $this->settings = $settings;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;

        if ($request = $requestStack->getCurrentRequest()) {
            $this->setContext($this->getFirewallName($request));
        } else {
            $this->setContext($this->context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(string $context): void
    {
        $this->context = $context;

        if (!\array_key_exists($this->context, $this->parameters)) {
            $this->parameters[$this->context] = [];
        }

        $contextParameterKey = 'chang_page_context_' . $this->context;

        if ($this->parameterBag->has($contextParameterKey)) {
            $this->parameters[$this->context] = \array_replace_recursive(
                $this->parameters[$this->context], (array)$this->parameterBag->get($contextParameterKey)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getClientIp(): ?string
    {
        return $this->getRequest() ? \trim(\explode(',', \strval($this->getRequest()->getClientIp()))[0]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function build(RequestConfiguration $configuration): void
    {
        $this->setContext($this->getFirewallName($configuration->getRequest()));

        $parameters['page'] = $configuration->getParameters()->get('vars', []);
        $this->parameters[$this->context] = \array_replace_recursive($this->parameters[$this->context], $parameters);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getFirewallName(Request $request): string
    {
        $paths = \explode('.', $request->attributes->get('_firewall_context', $this->context));

        return $paths[\count($paths) - 1];
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        if (\preg_match('/^\:/', $key)) {
            try {
                if (null !== $value = $this->settings->get($this->context . '_' . \str_replace(':', '', $key))) {
                    return $value;
                }
            } catch (\LogicException $e) {
                // no setting exception do nothing.
            }
        }

        if (!\array_key_exists($this->context, $this->parameters)) {
            return $default;
        }

        return (new Dot($this->parameters[$this->context]))->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value = null): void
    {
        if (\is_array($key)) {
            foreach ($key as $item => $val) {
                $this->set($item, $val);
            }

            return;
        }

        if (!\array_key_exists($this->context, $this->parameters)) {
            $this->parameters[$this->context] = [];
        }

        $dot = new Dot($this->parameters[$this->context]);
        $dot->set($key, $value);

        $this->parameters[$this->context] = $dot->all();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $parameters, ResourceInterface $resource): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($parameters as $key => $value) {
            if (\is_array($value)) {
                $parameters[$key] = $this->parse($value, $resource);
            }

            if (\is_string($value) && 0 === \strpos($value, 'resource.')) {
                $parameters[$key] = $accessor->getValue($resource, \substr($value, 9));
            }
        }

        return $parameters;
    }
}
