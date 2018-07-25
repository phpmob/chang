<?php

declare(strict_types=1);

namespace Chang\Security\Controller;

use Chang\Security\Form\Type\SecurityLoginType;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param FormFactoryInterface $formFactory
     * @param EngineInterface $templateEngine
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface $router
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        FormFactoryInterface $formFactory,
        EngineInterface $templateEngine,
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->formFactory = $formFactory;
        $this->templateEngine = $templateEngine;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request): Response
    {
        $session = $request->getSession();

        # https://github.com/FriendsOfSymfony/FOSOAuthServerBundle/blob/master/Resources/doc/a_note_about_security.md
        if ($request->attributes->has('_fos_oauth_server_authorize_enabled') && $session->has('_security.target_path')) {
            if (false !== strpos($session->get('_security.target_path'), $this->router->generate('fos_oauth_server_authorize'))) {
                $session->set('_fos_oauth_server.ensure_logout', true);
            }
        }

        $alreadyLoggedInRedirectRoute = $request->attributes->get('_sylius')['logged_in_route'] ?? null;

        if ($alreadyLoggedInRedirectRoute && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->router->generate($alreadyLoggedInRedirectRoute));
        }

        $lastError = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();
        $options = $request->attributes->get('_sylius');
        $template = $options['template'] ?? '@chang/security/login.html.twig';
        $formType = $options['form'] ?? SecurityLoginType::class;
        $form = $this->formFactory->createNamed('', $formType);

        return $this->templateEngine->renderResponse($template, [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'last_error' => $lastError,
            'login_check_route' => $request->attributes->get('_sylius')['login_check_route'] ?? null,
            'csrf_parameter' => $request->attributes->get('_sylius')['csrf_parameter'] ?? null,
            'csrf_token_id' => $request->attributes->get('_sylius')['csrf_token_id'] ?? null,
        ]);
    }

    /**
     * @param Request $request
     */
    public function checkAction(Request $request): void
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall.');
    }

    /**
     * @param Request $request
     */
    public function logoutAction(Request $request): void
    {
        throw new \RuntimeException('You must configure the logout path to be handled by the firewall.');
    }
}
