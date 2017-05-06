<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Middleware\RequestParser;
use Articstudio\Bitbucket\Middleware\RequestValidator;

class MiddlewareSystemProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['middle_request_parser'] = function($c) {
            return new RequestParser($c);
        };
        $container['middle_request_validator'] = function($c) {
            return new RequestValidator($c);
        };
    }

}
