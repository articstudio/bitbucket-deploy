<?php

namespace Articstudio\Bitbucket\Handler;

use Articstudio\Bitbucket\ContainerAwareTrait;
use Psr\Container\ContainerInterface as ContainerContract;

class AbstractHandler {

    use ContainerAwareTrait;

    public function __construct(ContainerContract $container) {
        $this->setContainer($container);
    }

}
