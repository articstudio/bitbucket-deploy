<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;
use Articstudio\Bitbucket\Middleware\Kernel;

class MiddlewareKernelProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['middle_kernel'] = function($c) {
            return new Kernel($c);
        };
    }

}
