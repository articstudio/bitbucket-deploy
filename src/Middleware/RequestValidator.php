<?php

namespace Articstudio\Bitbucket\Middleware;

use Articstudio\Bitbucket\Middleware\AbstractMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Articstudio\Bitbucket\Collection;
use Articstudio\Bitbucket\Header;
use Articstudio\Bitbucket\Payload;
use DomainException;

class RequestValidator extends AbstractMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        return $next($this->validate($request), $response);
    }

    private function validate(ServerRequestInterface $request) {
        $this->validateHeaders($this->container->headersInfo);
        $this->validatePayload($this->container->payload);
        $this->checkRepositoryExists($this->container->repositories, $this->container->payload);
        return $request;
    }

    private function validateHeaders(Header $headers) {
        $headers->validate();
    }

    private function validatePayload(Payload $payload) {
        $payload->validate();
    }

    private function checkRepositoryExists(Collection $repositories, Payload $payload) {
        $repository_name = $payload->get('repository')->get('full_name');
        if (!$repositories->has($repository_name)) {
            throw new DomainException(sprintf('Unknow repository "%s"', $repository_name));
        }
    }

}
