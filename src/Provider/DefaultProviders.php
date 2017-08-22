<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use Articstudio\Bitbucket\Provider\LoggerProvider;
use Articstudio\Bitbucket\Provider\RequestProvider;
use Articstudio\Bitbucket\Provider\ResponseProvider;
use Articstudio\Bitbucket\Provider\GitProvider;
use Articstudio\Bitbucket\Provider\MiddlewareProvider;
use Articstudio\Bitbucket\Provider\MiddlewareKernelProvider;
use Articstudio\Bitbucket\Provider\HandleFoundProvider;
use Articstudio\Bitbucket\Provider\HandleInvalidProvider;
use Articstudio\Bitbucket\Provider\HandleNotFoundProvider;
use Articstudio\Bitbucket\Provider\HandleExceptionProvider;
use Articstudio\Bitbucket\Provider\HandleErrorProvider;

class DefaultProviders extends AbstractServiceProvider {

    public static $defaults = [
        'logger' => 'registerLogger',
        'request' => 'registerRequest',
        'response' => 'registerResponse',
        'git' => 'registerGit',
        'middle_kernel' => 'registerMiddleKernel',
        'middle_system' => 'registerMiddleSystem',
        'middle' => 'registerMiddle',
        'handleFound' => 'registerHandleFound',
        'handleInvalid' => 'registerHandleInvalid',
        'handleNotFound' => 'registerHandleNotFound',
        'handleException' => 'registerHandleException',
        'handleError' => 'registerHandleError',
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

    private function registerHandleFound(Container $container) {
        $container->register(new HandleFoundProvider);
    }

    private function registerHandleInvalid(Container $container) {
        $container->register(new HandleInvalidProvider);
    }

    private function registerHandleNotFound(Container $container) {
        $container->register(new HandleNotFoundProvider);
    }

    private function registerHandleException(Container $container) {
        $container->register(new HandleExceptionProvider);
    }

    private function registerHandleError(Container $container) {
        $container->register(new HandleErrorProvider);
    }

}
