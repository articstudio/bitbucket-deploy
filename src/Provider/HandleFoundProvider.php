<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Handler\FoundHandler;

class HandleFoundProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['handleFound'] = function($c) {
            return new FoundHandler($c);
        };
    }

}
