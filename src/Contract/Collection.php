<?php

namespace Articstudio\Bitbucket\Contract;

interface Collection extends \ArrayAccess, \Countable, \IteratorAggregate {

    public function set($key, $value);

    public function get($key, $default = null);

    public function replace(array $items);

    public function all();

    public function has($key);

    public function remove($key);

    public function clear();

    public function isEmpty();
}
