<?php

namespace Articstudio\Bitbucket\Contract;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/*
 * Double pass middleware
 * https://github.com/php-fig/fig-standards/tree/master/proposed/http-middleware
 */

interface Middleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next);
}
