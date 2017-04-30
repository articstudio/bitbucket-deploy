<?php

namespace Articstudio\Bitbucket\Exception\Container;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface as NotFoundExceptionContract;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionContract {

}
