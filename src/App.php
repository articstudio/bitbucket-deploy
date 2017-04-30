<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\ContainerAwareTrait;
use Articstudio\Bitbucket\Container;
use Psr\Container\ContainerInterface as ContainerContract;
use Articstudio\Bitbucket\Exception\App\InvalidArgumentException;
use Articstudio\Bitbucket\Exception\Middleware\NotFoundException as MiddlewareNotFound;
use Articstudio\Bitbucket\Provider\DefaultProviders;
use Articstudio\Bitbucket\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Throwable;

class App {

    use ContainerAwareTrait;

    const VERSION = '1.0.0';

    private $response;

    public function __construct($container = []) {
        if (is_array($container)) {
            $container = new Container($container);
        }
        if (!$container instanceof ContainerContract) {
            throw new InvalidArgumentException(sprintf('Expected a "%s"', ContainerContract::class));
        }
        $container['version'] = self::VERSION;
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
                throw new MiddlewareNotFound(sprintf('Middleware error while retrieving "%s"', $middleware), null, $exception);
            } catch (Throwable $exception) {
                throw new MiddlewareNotFound(sprintf('Middleware error while retrieving "%s"', $middleware), null, $exception);
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

        //$response->getBody()->write('<h2>Found</h2>');
        //$response->getBody()->write('<h4>Headers</h4><pre>' . print_r($this->container->headersInfo->all(), true) . '</pre>');
        //$response->getBody()->write('<h4>Payload</h4><pre>' . print_r($this->container->payload, true) . '</pre>');
        //$this->container->logger->info('Found', $this->container->payload);
        return $response;
    }

    protected function handleException(Exception $exception, ServerRequestInterface $request, ResponseInterface $response) {
        $this->container->logger->warning($exception->getMessage(), $exception->getTrace());

        $response->getBody()->write('<h2>Exception</h2>');
        $response->getBody()->write('<h4>' . $exception->getMessage() . '</h4><pre>' . $exception->getTraceAsString() . '</pre>');
        $response->getBody()->write('<h4>Headers</h4><pre>' . print_r($this->container->headersInfo->all(), true) . '</pre>');
        $response->getBody()->write('<h4>Payload</h4><pre>' . print_r($this->container->payload, true) . '</pre>');

        return $response->withStatus(500, $exception->getMessage());
    }

    protected function handleError(Throwable $exception, ServerRequestInterface $request, ResponseInterface $response) {
        $this->container->logger->error($exception->getMessage(), $exception->getTrace());

        $response->getBody()->write('<h2>Error</h2>');
        $response->getBody()->write('<h4>' . $exception->getMessage() . '</h4><pre>' . $exception->getTraceAsString() . '</pre>');
        $response->getBody()->write('<h4>Headers</h4><pre>' . print_r($this->container->headersInfo->all(), true) . '</pre>');
        $response->getBody()->write('<h4>Payload</h4><pre>' . print_r($this->container->payload, true) . '</pre>');

        return $response->withStatus(500, $exception->getMessage());
    }

    private function respond() {
        $response = new Response($this->response);
        $response->respond();

        /* echo '<pre>';
          print_r($this->container);
          echo '</pre>'; */
    }

}
