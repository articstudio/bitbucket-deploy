<?php

namespace Articstudio\Bitbucket\Contract;

use Psr\Container\ContainerInterface as ContainerContract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FoundHandler {

    public function __construct(ContainerContract $container);

    public function handle(ServerRequestInterface $request, ResponseInterface $response);
}
