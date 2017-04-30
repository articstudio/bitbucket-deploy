<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;
use Articstudio\Bitbucket\Provider\LoggerProvider;
use Articstudio\Bitbucket\Provider\RequestProvider;
use Articstudio\Bitbucket\Provider\ResponseProvider;
use Articstudio\Bitbucket\Provider\GitProvider;
use Articstudio\Bitbucket\Provider\MiddlewareProvider;
use Articstudio\Bitbucket\Provider\MiddlewareKernelProvider;

class DefaultProviders extends AbstractServiceProvider {

    public static $defaults = [
        'logger' => 'registerLogger',
        'request' => 'registerRequest',
        'response' => 'registerResponse',
        'git' => 'registerGit',
        'middle_kernel' => 'registerMiddleKernel',
        'middle_system' => 'registerMiddleSystem',
        'middle' => 'registerMiddle',
    ];

    use Manager\ManagerByMethodsTrait;

    private function registerLogger(Container $container) {
        $container->register(new LoggerProvider);
    }

    private function registerRequest(Container $container) {
        $container->register(new RequestProvider);
    }

    private function registerResponse(Container $container) {
        $container->register(new ResponseProvider);
    }

    private function registerGit(Container $container) {
        $container->register(new GitProvider);
    }

    private function registerMiddleKernel(Container $container) {
        $container->register(new MiddlewareKernelProvider);
    }

    private function registerMiddleSystem(Container $container) {
        $container->register(new MiddlewareSystemProvider);
    }

    private function registerMiddle(Container $container) {
        $container->register(new MiddlewareProvider);
    }

}
