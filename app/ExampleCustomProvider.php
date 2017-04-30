<?php

namespace App;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;

class ExampleCustomProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['example'] = function() use ($container) {
            return 'example';
        };
    }

}
