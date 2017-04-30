<?php

namespace Articstudio\Bitbucket\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response {

    private static $EMPTY_STATUS = [204, 205, 304];
    private static $CHUNK_SIZE = 4096;
    private $response;
    private $isEmpty = false;
    private $length;

    public function __construct(ResponseInterface $response) {
        $this->response = $response;
    }

    public function respond() {
        $this->parse()
                ->headers()
                ->body();
    }

    private function parse() {
        if ($this->isEmptyResponse()) {
            $this->isEmpty = true;
            $this->response = $this->response->withoutHeader('Content-Type')->withoutHeader('Content-Length');
            $this->length = $this->response->getHeaderLine('Content-Length');
        }
        return $this;
    }

    private function headers() {
        if (!headers_sent()) {
            $this->prevent_cache();
            $this->header_status();
            $this->header_custom();
        }
        return $this;
    }

    private function prevent_cache() {
        $this->response = $this->response->withHeader('Cache-Control', [
                    'no-store, no-cache, must-revalidate, max-age=0',
                    'post-check=0, pre-check=0'
                ])
                ->withHeader('Pragma', 'no-cache');
    }

    private function header_status() {
        header(sprintf('HTTP/%s %s %s', $this->response->getProtocolVersion(), $this->response->getStatusCode(), $this->response->getReasonPhrase()));
    }

    private function header_custom() {
        foreach ($this->response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    private function isEmptyResponse() {
        if (method_exists($this->response, 'isEmpty')) {
            return $this->response->isEmpty();
        }
        return in_array($this->response->getStatusCode(), self::$EMPTY_STATUS);
    }

    private function body() {
        if (!$this->isEmpty) {
            $body = $this->response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $this->bodyLength($body);
            $this->write($body);
        }
        return $this;
    }

    private function bodyLength(StreamInterface $body) {
        if (!$this->length) {
            $this->length = $body->getSize();
        }
    }

    private function write(StreamInterface $body) {
        if (isset($this->length) && $this->length) {
            $amount = $this->length;
            while ($amount > 0 && !$body->eof()) {
                $data = $body->read(min(self::$CHUNK_SIZE, $amount));
                echo $data;
                $amount -= strlen($data);
                if (connection_status() != CONNECTION_NORMAL) {
                    break;
                }
            }
        } else {
            while (!$body->eof()) {
                echo $body->read(self::$CHUNK_SIZE);
                if (connection_status() != CONNECTION_NORMAL) {
                    break;
                }
            }
        }
    }

}
