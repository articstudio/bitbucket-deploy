<?php

namespace Articstudio\Bitbucket\Contract;

use Articstudio\Bitbucket\Container;

interface ServiceProvider {

    public function register(Container $container);
}
