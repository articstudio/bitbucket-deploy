<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Articstudio\Bitbucket\Container;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Stream;

class RequestProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['request'] = function($c) {
            $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
            $headers = function_exists('getallheaders') ? getallheaders() : [];
            $uri = ServerRequest::getUriFromGlobals();
            $body = new Stream(fopen('php://input', 'r'));
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
            $serverRequest = new ServerRequest($method, $uri, $headers, $body, $protocol, $_SERVER);
            return $serverRequest
                            ->withCookieParams($_COOKIE)
                            ->withQueryParams($_GET)
                            ->withParsedBody($_POST)
                            ->withUploadedFiles(ServerRequest::normalizeFiles($_FILES));
        };
    }

}
