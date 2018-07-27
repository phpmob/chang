<?php

declare(strict_types=1);

namespace Chang\Messenger\Controller;

use Chang\Messenger\Manager\MessageManagerInterface;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends ResourceController
{
    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @param MessageManagerInterface $messageManager
     */
    public function setMessageManager(MessageManagerInterface $messageManager): void
    {
        $this->messageManager = $messageManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function markAllAsReadAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);

        $this->messageManager->markAllAsRead($this->getUser());

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create(null, Response::HTTP_NO_CONTENT));
        }

        $this->flashHelper->addSuccessFlash($configuration, ResourceActions::UPDATE);

        return $this->redirectHandler->redirectToIndex($configuration);
    }

    /**
     * @param Request $request
     * @param string $action
     *
     * @return Response
     */
    public function markAsAction(Request $request, string $action): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        $action = 'markAs' . ucfirst($action);
        $this->messageManager->$action($resource, $this->getUser());

        if (!$configuration->isHtmlRequest()) {
            $view = $configuration->getParameters()->get('return_content', false) ? View::create($resource, Response::HTTP_OK) : View::create(null, Response::HTTP_NO_CONTENT);

            return $this->viewHandler->handle($configuration, $view);
        }

        $this->flashHelper->addSuccessFlash($configuration, ResourceActions::UPDATE, $resource);

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function viewAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->findOr404($configuration);

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);
        $this->messageManager->markAsRead($resource, $this->getUser());

        $view = View::create($resource);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::SHOW . '.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    $this->metadata->getName() => $resource,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}
