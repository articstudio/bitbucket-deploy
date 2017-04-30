<?php

namespace Articstudio\Bitbucket\Provider\Manager;

use Pimple\Container;
use Articstudio\Bitbucket\Exception\Provider\NotFoundException;
use Exception;
use Throwable;

trait ManagerByProveidersTrait {

    private $providers = [];

    public function __construct(array $providers = []) {
        $this->providers = $providers;
    }

    public function register(Container $container) {
        foreach ($this->providers AS $provider) {
            $this->registerProvider($container, $provider);
        }
    }

    public function add($provider) {
        $this->providers[] = $provider;
    }

    private function registerProvider(Container $container, $provider) {
        if (is_string($provider)) {
            try {
                $provider = new $provider;
            } catch (Exception $exception) {
                throw new NotFoundException(sprintf('Provider error while retrieving "%s"', $provider), null, $exception);
            } catch (Throwable $exception) {
                throw new NotFoundException(sprintf('Provider error while retrieving "%s"', $provider), null, $exception);
            }
        }
        call_user_func_array(array($provider, 'register'), array($container));
    }

}
