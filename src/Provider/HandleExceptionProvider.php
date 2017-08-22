<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Handler\ExceptionHandler;

class HandleExceptionProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['handleException'] = function($c) {
            return new ExceptionHandler($c);
        };
    }

}
