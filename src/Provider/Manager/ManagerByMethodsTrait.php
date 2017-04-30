<?php

namespace Articstudio\Bitbucket\Provider\Manager;

use Pimple\Container;

trait ManagerByMethodsTrait {

    private $methods = [];

    public function __construct(array $methods = []) {
        $this->methods = $methods;
    }

    public function register(Container $container) {
        if (!isset($this->methods) || !is_array($this->methods)) {
            return;
        }
        foreach ($this->methods AS $alias => $method) {
            $this->registerByMethod($container, $method, $alias);
        }
    }

    public function add($method, $alias = null) {
        if ($alias) {
            $this->methods[$alias] = $method;
            return;
        }
        $this->methods[] = $method;
    }

    private function registerByMethod(Container $container, $method, $alias) {
        if (is_string($alias) && $container->has($alias)) {
            return;
        }
        call_user_func_array(array($this, $method), array($container));
    }

}
