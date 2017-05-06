<?php

namespace Articstudio\Bitbucket\Middleware;

use Articstudio\Bitbucket\Contract\Middleware as MiddlewareContract;
use Articstudio\Bitbucket\ContainerAwareTrait;
use Articstudio\Bitbucket\Container;

abstract class AbstractMiddleware implements MiddlewareContract {

    use ContainerAwareTrait;

    public function __construct(Container $container) {
        $this->setContainer($container);
    }

}
