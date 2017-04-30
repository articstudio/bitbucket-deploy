<?php

namespace Articstudio\Bitbucket;

trait ContainerAwareTrait {

    protected $container;

    public function getContainer() {
        return $this->container;
    }

    public function setContainer(Container $container) {
        $this->container = $container;
    }

}
