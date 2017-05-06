<?php

namespace Articstudio\Bitbucket\Handler;

use Articstudio\Bitbucket\Handler\AbstractHandler;
use Articstudio\Bitbucket\Contract\FoundHandler as FoundHandlerContract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class FoundHandler extends AbstractHandler implements FoundHandlerContract {

    public function handle(ServerRequestInterface $request, ResponseInterface $response) {
        return $response;
    }

}
