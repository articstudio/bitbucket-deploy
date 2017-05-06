<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\ContainerAwareTrait;
use Articstudio\Bitbucket\Container;
use Psr\Container\ContainerInterface as ContainerContract;
use Articstudio\Bitbucket\Provider\DefaultProviders;
use Articstudio\Bitbucket\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Articstudio\Bitbucket\Exception\App\InvalidArgumentException as AppInvalidArgumentException;
use Articstudio\Bitbucket\Exception\Middleware\NotFoundException as MiddlewareNotFoundException;
use Articstudio\Bitbucket\Exception\Middleware\WrongPayloadException;
use Articstudio\Bitbucket\Exception\Middleware\InvalidHeaderException;
use Articstudio\Bitbucket\Exception\Middleware\InvalidPayloadException;
use Articstudio\Bitbucket\Exception\Middleware\InvalidRepositoryException;
use Articstudio\Bitbucket\Exception\Deploy\NotFoundException as DeployNotFoundException;
use Articstudio\Bitbucket\Exception\Deploy\RuntimeException as DeployRuntimeException;
use Exception;
use Throwable;

class App {

    use ContainerAwareTrait;

    private $response;

    public function __construct($container = []) {
        if (is_array($container)) {
            $container = new Container($container);
        }
        if (!$container instanceof ContainerContract) {
            throw new AppInvalidArgumentException(sprintf('Expected a "%s"', ContainerContract::class));
        }
        $this->setContainer($container);
    }

    public function run() {
        $this->registerProviders()
                ->middlewares()
                ->process()
                ->respond();
    }

    private function registerProviders() {
        $provider = new DefaultProviders(DefaultProviders::$defaults);
        $provider->register($this->container);
        return $this;
    }

    private function middlewares() {
        $middlewares = $this->container->middlewares->getIterator();
        foreach ($middlewares as $middleware) {
            $this->addMiddleware($middleware);
        }
        $this->systemMiddlewares();
        return $this;
    }

    private function addMiddleware($middleware) {
        if (is_string($middleware)) {
            try {
                $middleware = new $middleware($this->container);
            } catch (Exception $exception) {
                throw new MiddlewareNotFoundException(sprintf('Middleware error while retrieving "%s"', $middleware), null, $exception);
            } catch (Throwable $exception) {
                throw new MiddlewareNotFoundException(sprintf('Middleware error while retrieving "%s"', $middleware), null, $exception);
            }
        }
        $this->container->middle->add($middleware);
    }

    private function systemMiddlewares() {
        $this->container->middle->add($this->container->middle_request_validator);
        $this->container->middle->add($this->container->middle_request_parser);
    }

    private function process() {
        $request = $this->container->request;
        $response = $this->container->response;
        try {
            $this->response = $this->handleFound($request, $this->container->middle->call($request, $response));
        } catch (Exception $exception) {
            $this->response = $this->handleException($exception, $request, $response);
        } catch (Throwable $exception) {
            $this->response = $this->handleError($exception, $request, $response);
        }
        return $this;
    }

    protected function handleFound(ServerRequestInterface $request, ResponseInterface $response) {
        return $this->container->handleFound->handle($request, $response);
    }

    protected function handleException(Exception $exception, ServerRequestInterface $request, ResponseInterface $response) {
        if ($exception instanceof WrongPayloadException) {
            return $this->container->handleInvalid->handle($exception, $request, $response);
        } else if ($exception instanceof InvalidHeaderException) {
            return $this->container->handleInvalid->handle($exception, $request, $response);
        } else if ($exception instanceof InvalidPayloadException) {
            return $this->container->handleInvalid->handle($exception, $request, $response);
        } else if ($exception instanceof InvalidRepositoryException) {
            return $this->container->handleInvalid->handle($exception, $request, $response);
        } else if ($exception instanceof DeployNotFoundException) {
            return $this->container->handleNotFound->handle($exception, $request, $response);
        } else if ($exception instanceof DeployRuntimeException) {
            return $this->container->handleNotFound->handle($exception, $request, $response);
        }
        return $this->container->handleException->handle($exception, $request, $response);
    }

    protected function handleError(Throwable $exception, ServerRequestInterface $request, ResponseInterface $response) {
        return $this->container->handleError->handle($exception, $request, $response);
    }

    private function respond() {
        $response = new Response($this->response);
        $response->respond();
    }

}
