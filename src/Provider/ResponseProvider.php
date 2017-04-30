<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;
use GuzzleHttp\Psr7\Response;

class ResponseProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['response'] = function($c) {
            return new Response;
        };
    }

}
