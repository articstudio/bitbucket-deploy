<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Handler\ErrorHandler;

class HandleErrorProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['handleError'] = function($c) {
            return new ErrorHandler($c);
        };
    }

}
