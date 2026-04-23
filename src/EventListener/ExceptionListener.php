<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $statusCode = 500;
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            $statusCode = 401;
        }
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\BadRequestHttpException) {
            $statusCode = 400;
        }

        $response = new JsonResponse([
            'statusCode' => $statusCode,
            'message' => $exception->getMessage(),
        ], $statusCode);
        $event->setResponse($response);
    }
}
