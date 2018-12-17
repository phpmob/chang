<?php

declare(strict_types=1);

namespace Chang\Messenger\Worker;

use Chang\Messenger\Message\AbstractMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class WorkerController
{
    /**
     * @var HashHandlerInterface
     */
    private $hashHandler;

    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var SerializerInterface
     */
    private $decoder;

    public function __construct(HashHandlerInterface $hashHandler, SerializerInterface $decoder, MessageBusInterface $bus)
    {
        $this->hashHandler = $hashHandler;
        $this->decoder = $decoder;
        $this->bus = $bus;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handleAction(Request $request): Response
    {
        $extras = $request->request->get('extras', []);

        if (!$this->hashHandler->verify($extras['worker'] ?? [], false)) {
            return JsonResponse::create(['invalid' => true, 'message' => 'Invalid hash verify.'], 500);
        }

        try {
            $result = $this->bus->dispatch($this->decoder->decode([
                'body' => $request->getContent(),
                'headers' => ['type' => $request->headers->get('x-chang-msg-type')],
            ])->with(new ReceivedStamp()));
        } catch (\Exception $e) {
            return JsonResponse::create(['message' => $e->getMessage()], 500);
        }

        $this->hashHandler->flush();

        if ($result instanceof AbstractMessage && $result->isExpired()) {
            return JsonResponse::create(['expired' => true, 'message' => 'Message was expired.'], 500);
        }

        return JsonResponse::create([], 200);
    }
}
