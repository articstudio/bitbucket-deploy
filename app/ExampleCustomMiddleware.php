<?php

namespace App;

use Articstudio\Bitbucket\Middleware\AbstractMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExampleCustomMiddleware extends AbstractMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        $req = $request->withAddedHeader('ExampleMiddleware', 1);
        $res = $response->withAddedHeader('ExampleMiddleware', 1);
        return $next($req, $res);
    }

}
