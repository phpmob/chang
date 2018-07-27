<?php

declare(strict_types=1);

namespace Chang\User\Controller;

use Chang\User\UserEvents;
use Chang\User\Form\Model\ChangeUser;
use Chang\User\Form\Type\ChangeUserType;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\UserBundle\Controller\UserController as BaseUserController;
use Sylius\Component\User\Security\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;

class UserController extends BaseUserController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changeAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if (!$this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException('You have to be registered user to access this section.');
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $changeUser = new ChangeUser($user);
        $formType = $this->getSyliusAttribute($request, 'form', ChangeUserType::class);
        $form = $this->createResourceForm($configuration, $formType, $changeUser);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            return $this->handleChange($request, $configuration, $changeUser);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($form, Response::HTTP_BAD_REQUEST));
        }

        $template = $this->getSyliusAttribute($request, 'template', null);

        Assert::notNull($template, 'Template is not configured.');

        return $this->container->get('templating')->renderResponse($template, [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param RequestConfiguration $configuration
     * @param ChangeUser $data
     *
     * @return Response
     */
    protected function handleChange(Request $request, RequestConfiguration $configuration, ChangeUser $data): Response
    {
        $user = $data->getUser();
        $dispatcher = $this->container->get('event_dispatcher');

        if ($data->isUsernameChanged()) {
            $dispatcher->dispatch(UserEvents::PRE_USERNAME_CHANGE, new GenericEvent($data));
            $user->setUsername($data->getUsername());
        }

        if ($data->isEmailChanged()) {
            $dispatcher->dispatch(UserEvents::PRE_EMAIL_CHANGE, new GenericEvent($data));
            $user->setEmail($data->getEmail());

            if ($this->container->get('chang.option_resolver')->get('user.verify_email_change.enabled')) {
                /** @var GeneratorInterface $tokenGenerator */
                $tokenGenerator = $this->container->get(sprintf('sylius.%s.token_generator.email_verification', $this->metadata->getName()));
                $user->setEmailVerificationToken($tokenGenerator->generate());
                $user->setVerifiedAt(null);
            }
        }

        $this->manager->flush();

        if ($data->isUsernameChanged()) {
            $dispatcher->dispatch(UserEvents::POST_USERNAME_CHANGE, new GenericEvent($data));
        }

        if ($data->isEmailChanged()) {
            $dispatcher->dispatch(UserEvents::POST_EMAIL_CHANGE, new GenericEvent($data));

            if ($user->getEmailVerificationToken()) {
                $dispatcher->dispatch(UserEvents::REQUEST_VERIFICATION_TOKEN, new GenericEvent($user));
            }
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create(null, Response::HTTP_NO_CONTENT));
        }

        if ($data->isUserChanged()) {
            $this->addTranslatedFlash('success', 'chang.user.changed');
        }

        $redirectRouteName = $this->getSyliusAttribute($request, 'redirect', null);

        Assert::notNull($redirectRouteName, 'Redirect is not configured.');

        return $this->redirectHandler->redirectToResource($configuration, $user);
    }

    /**
     * {@override}
     * {@inheritdoc}
     */
    protected function createResourceForm(RequestConfiguration $configuration, $type, $object): FormInterface
    {
        $options = [];

        if (is_array($type)) {
            $options = $type['options'];
            $type = $type['type'];
        }

        if (!$configuration->isHtmlRequest()) {
            $options['csrf_protection'] = false;

            return $this->container->get('form.factory')->createNamed('', $type, $object, $options);
        }

        return $this->container->get('form.factory')->create($type, $object, $options);
    }

    /**
     * @param Request $request
     * @param string $attribute
     * @param mixed $default
     *
     * @return mixed
     */
    private function getSyliusAttribute(Request $request, string $attribute, $default = null)
    {
        $attributes = $request->attributes->get('_sylius');

        return $attributes[$attribute] ?? $default;
    }
}
