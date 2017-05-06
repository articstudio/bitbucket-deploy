<?php

namespace Articstudio\Bitbucket\Exception;

use Throwable;

trait TrowByTrait {

    protected final function thrownByParent(Throwable $exception, $concrete = null, $method = null) {
        return $this->thrownBy($exception, parent::class, $concrete, $method);
    }

    protected final function thrownBy(Throwable $exception, $by, $concrete = null, $method = null) {
        $trace = $exception->getTrace()[0];
        if ($concrete && !($exception instanceof $concrete)) {
            return false;
        }
        if ($method && $trace['function'] !== $method) {
            return false;
        }
        return $trace['class'] === $by;
    }

}
