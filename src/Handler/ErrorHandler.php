<?php

namespace Articstudio\Bitbucket\Handler;

use Articstudio\Bitbucket\Handler\AbstractHandler;
use Articstudio\Bitbucket\Contract\ThrowableHandler as ThrowableHandlerContract;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler extends AbstractHandler implements ThrowableHandlerContract {

    public function handle(Throwable $throwable, ServerRequestInterface $request, ResponseInterface $response) {
        $this->container->logger->error($throwable->getMessage(), $throwable->getTrace());
        $response->getBody()->write('<h2>Error</h2>');
        $response->getBody()->write('<h4>' . $throwable->getMessage() . '</h4><pre>' . $throwable->getTraceAsString() . '</pre>');
        return $response->withStatus(500, $throwable->getMessage());
    }

}
