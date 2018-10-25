<?php

declare(strict_types=1);

namespace Chang\SecurityAudit;

use Chang\GeoIp\DataSourceInterface;
use Chang\SecurityAudit\Model\LoginInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;

class AuditManager implements AuditManagerInterface
{
    /**
     * @var DataSourceInterface
     */
    private $geoIpDataSource;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var FactoryInterface
     */
    private $loginAuditFactory;

    /**
     * @var ObjectManager
     */
    private $loginAuditManager;

    /**
     * @var RepositoryInterface
     */
    private $loginAuditRepository;

    /**
     * @var FirewallMapInterface
     */
    private $firewallMap;

    public function __construct(
        DataSourceInterface $geoIpDataSource,
        TokenStorageInterface $tokenStorage,
        FactoryInterface $loginAuditFactory,
        ObjectManager $loginAuditManager,
        RepositoryInterface $loginAuditRepository,
        FirewallMapInterface $firewallMap
    )
    {
        $this->geoIpDataSource = $geoIpDataSource;
        $this->tokenStorage = $tokenStorage;
        $this->loginAuditFactory = $loginAuditFactory;
        $this->loginAuditManager = $loginAuditManager;
        $this->loginAuditRepository = $loginAuditRepository;
        $this->firewallMap = $firewallMap;
    }

    private function createLoginAudit(Request $request): LoginInterface
    {
        $clientIp = \trim(\explode(',', \strval($request->getClientIp()))[0]);
        $token = $this->tokenStorage->getToken();
        $geoIp = $this->geoIpDataSource->getData($clientIp);

        /** @var LoginInterface $login */
        $login = $this->loginAuditFactory->createNew();
        $login->setFirewall($this->getFirewallName($request) ?? 'NA');
        $login->setUsername($token->getUsername());
        $login->setClientIp($clientIp);
        $login->setMeta(['headers' => $request->headers->all()]);
        $login->setUserAgent($request->headers->get('user-agent', 'NA'));

        $login->setCountry($geoIp->getCountry() ?? 'NA');
        $login->setCountryCode($geoIp->getCountryCode() ?? 'NA');
        $login->setCity($geoIp->getCity() ?? 'NA');
        $login->setZip($geoIp->getZip() ?? 'NA');

        $login->setLifetime((int)ini_get('session.gc_maxlifetime'));

        return $login;
    }

    private function getFirewallName(Request $request): ?string
    {
        if ($this->firewallMap instanceof FirewallMap) {
            return $this->firewallMap->getFirewallConfig($request)->getName();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function login(Request $request, string $sessionId): void
    {
        if (!$login = $this->createLoginAudit($request)) {
            return;
        }

        $login->setLoginAt(new \DateTime());
        $login->setSessionId($sessionId);

        $this->loginAuditManager->persist($login);
        $this->loginAuditManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function logout(string $sessionId): void
    {
        /** @var LoginInterface $login */
        if (!$login = $this->loginAuditRepository->findOneBy(['sessionId' => $sessionId])) {
            return;
        }

        $login->setLogoutAt(new \DateTime());

        $this->loginAuditManager->persist($login);
        $this->loginAuditManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function kicking(string $sessionId): void
    {
        /** @var LoginInterface $login */
        if (!$login = $this->loginAuditRepository->findOneBy(['sessionId' => $sessionId])) {
            return;
        }

        $login->setKicked(true);
        $login->setLogoutAt(new \DateTime());

        $this->loginAuditManager->persist($login);
        $this->loginAuditManager->flush();
    }
}
