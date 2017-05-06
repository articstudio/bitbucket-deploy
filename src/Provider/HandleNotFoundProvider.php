<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Handler\NotFoundHandler;

class HandleNotFoundProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['handleNotFound'] = function($c) {
            return new NotFoundHandler($c);
        };
    }

}
