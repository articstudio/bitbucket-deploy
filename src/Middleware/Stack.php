<?php

namespace Articstudio\Bitbucket\Middleware;

use SplStack;
use SplDoublyLinkedList;
use Articstudio\Bitbucket\Exception\Middleware\StackLocked;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Articstudio\Bitbucket\Exception\Middleware\UnexpectedResponse;

class Stack {

    protected $stack;
    protected $lock = false;

    public function __construct(callable $kernel = null) {
        $this->initStack($kernel);
    }

    public function add(callable $callable) {
        if ($this->lock) {
            throw new StackLocked('Middle ware can not be added, stack is locked');
        }
        $this->initStack();
        $next = $this->stack->top();
        $this->stack[] = function (ServerRequestInterface $request, ResponseInterface $response) use ($callable, $next) {
            $result = call_user_func($callable, $request, $response, $next);
            if ($result instanceof ResponseInterface === false) {
                throw new UnexpectedResponse(sprintf('Middleware must return instance of "%s"', ResponseInterface::class));
            }
            return $result;
        };

        return $this;
    }

    public function call(ServerRequestInterface $request, ResponseInterface $response) {
        $this->initStack();
        $start = $this->stack->top();
        $this->lock = true;
        $result = $start($request, $response);
        $this->lock = false;
        return $result;
    }

    protected function initStack(callable $kernel = null) {
        if (!is_null($this->stack)) {
            return;
        }
        $this->stack = new SplStack;
        $this->stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);
        if (!is_null($kernel)) {
            $this->stack[] = $kernel;
        }
    }

}
