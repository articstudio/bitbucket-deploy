<?php

namespace Articstudio\Bitbucket\Exception;

trait ParentCatchTrait {

    protected function isParentException(\Throwable $exception, $functionName = null, $parentClass = null) {
        $trace = $exception->getTrace()[0];

        if ($functionName && $trace['function'] !== $functionName) {
            return false;
        }

        if (($parentClass && $trace['class'] !== $parentClass) || (!$parentClass && $trace['class'] !== parent::class)) {
            return false;
        }

        return true;
    }

}
