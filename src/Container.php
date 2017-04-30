<?php

namespace Articstudio\Bitbucket;

use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface as ContainerContract;
use Articstudio\Bitbucket\Exception\Container\NotFoundException;
use Articstudio\Bitbucket\Exception\Container\Exception as ContainerException;
use Articstudio\Bitbucket\Collection;
use Articstudio\Bitbucket\Provider\AppProvider;

class Container extends PimpleContainer implements ContainerContract {

    use Exception\ParentCatchTrait;

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $settings = isset($values['settings']) ? $values['settings'] : [];
        $this->registerSettings($settings);
        $providers = isset($values['providers']) ? $values['providers'] : [];
        $this->registerProviders($providers);
        $middlewares = isset($values['middlewares']) ? $values['middlewares'] : [];
        $this->registerMiddlewares($middlewares);
        $repositories = isset($values['repositories']) ? $values['repositories'] : [];
        $this->registerRepositories($repositories);
    }

    public function get($id) {
        if (false && !$this->offsetExists($id)) {
            throw new NotFoundException(sprintf('Identifier "%s" is not defined!', $id));
        }
        try {
            return $this->offsetGet($id);
        } catch (\InvalidArgumentException $exception) {
            if ($this->isParentException($exception, 'offsetGet')) {
                throw new ContainerException(sprintf('Container error while retrieving "%s"', $id), null, $exception);
            } else {
                throw $exception;
            }
        }
    }

    public function has($id) {
        return $this->offsetExists($id);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __isset($name) {
        return $this->has($name);
    }

    private function registerSettings(array $settings) {
        $this['settings'] = function () use ($settings) {
            return new Collection($settings);
        };
    }

    private function registerProviders(array $providers) {
        $provider = new AppProvider($providers);
        $provider->register($this);
    }

    private function registerMiddlewares(array $middlewares) {
        $this['middlewares'] = function () use ($middlewares) {
            return new Collection($middlewares);
        };
    }

    private function registerRepositories(array $repositories) {
        $this['repositories'] = function () use ($repositories) {
            return new Collection($repositories);
        };
    }

}
