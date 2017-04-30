<?php

namespace Articstudio\Bitbucket\Middleware;

use Articstudio\Bitbucket\Middleware\AbstractMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Articstudio\Bitbucket\Header;
use Articstudio\Bitbucket\Payload;
use DomainException;

class RequestParser extends AbstractMiddleware {

    const ASSOCIATIVE = true;
    const DEPTH = 512;
    const JSON_OPTIONS = 0;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        return $next($this->parse($request), $response);
    }

    private function parse(ServerRequestInterface $request) {
        $this->container['headersInfo'] = $this->parseHeaders($request);
        $this->container['payload'] = $this->parsePayload($request->getBody());
        return $request;
    }

    private function parseHeaders(ServerRequestInterface $request) {
        return new Header([
            'event' => $request->getHeaderLine('X-Event-Key'),
            'user_agent' => $request->getHeaderLine('User-Agent'),
            'request_uuid' => $request->getHeaderLine('X-Request-UUID'),
            'attempt' => $request->getHeaderLine('X-Attempt-Number'),
            'hook_uuid' => $request->getHeaderLine('X-Hook-UUID'),
            'content_type' => $request->getHeaderLine('Content-Type'),
        ]);
    }

    private function parsePayload(StreamInterface $stream) {
        $json = trim($stream->getContents());
        $data = [];
        if (!empty($json)) {
            $data = json_decode($json, self::ASSOCIATIVE, self::DEPTH, self::JSON_OPTIONS);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new DomainException(json_last_error_msg());
            }
        }
        return new Payload($data ?: []);
    }

}
