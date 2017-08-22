<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Handler\InvalidHandler;

class HandleInvalidProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['handleInvalid'] = function($c) {
            return new InvalidHandler($c);
        };
    }

}
