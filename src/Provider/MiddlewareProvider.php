<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Middleware\Stack;

class MiddlewareProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['middle'] = function($c) {
            return new Stack($c->middle_kernel);
        };
    }

}
