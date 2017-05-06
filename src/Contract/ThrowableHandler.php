<?php

namespace Articstudio\Bitbucket\Contract;

use Psr\Container\ContainerInterface as ContainerContract;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ThrowableHandler {

    public function __construct(ContainerContract $container);

    public function handle(Throwable $throwable, ServerRequestInterface $request, ResponseInterface $response);
}
