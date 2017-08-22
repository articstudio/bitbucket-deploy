<?php

namespace Articstudio\Bitbucket\Handler;

use Articstudio\Bitbucket\Handler\AbstractHandler;
use Articstudio\Bitbucket\Contract\ThrowableHandler as ThrowableHandlerContract;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExceptionHandler extends AbstractHandler implements ThrowableHandlerContract {

    public function handle(Throwable $throwable, ServerRequestInterface $request, ResponseInterface $response) {
        $this->container->logger->warning($throwable->getMessage(), $throwable->getTrace());
        $response->getBody()->write('<h2>Exception</h2>');
        $response->getBody()->write('<h4>' . $throwable->getMessage() . '</h4><pre>' . $throwable->getTraceAsString() . '</pre>');
        $response->getBody()->write('<h4>Headers</h4><pre>' . print_r($this->container->headersInfo->all(), true) . '</pre>');
        $response->getBody()->write('<h4>Payload</h4><pre>' . print_r($this->container->payload, true) . '</pre>');
        return $response->withStatus(500, $throwable->getMessage());
    }

}
