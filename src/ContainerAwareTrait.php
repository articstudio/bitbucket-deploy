<?php

namespace Articstudio\Bitbucket;

use Psr\Container\ContainerInterface as ContainerContract;

trait ContainerAwareTrait {

    protected $container;

    public function getContainer(): ContainerContract {
        return $this->container;
    }

    public function setContainer(ContainerContract $container) {
        $this->container = $container;
    }

}
