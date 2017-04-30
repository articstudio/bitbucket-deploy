<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\Contract\Collection as CollectionContract;
use ArrayIterator;

class Collection implements CollectionContract {

    protected $data = [];

    public function __construct(array $items = []) {
        $this->setMultiple($items);
    }

    public function setMultiple(array $items = []) {
        foreach ($items as $k => $v) {
            $this->set($k, $v);
        }
        return true;
    }

    public function set($key, $value) {
        return ($this->data[$key] = $value);
    }

    public function get($key, $default = null) {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function replace(array $items) {
        return $this->setMultiple($items);
    }

    public function all() {
        return $this->data;
    }

    public function keys() {
        return array_keys($this->data);
    }

    public function has($key) {
        return array_key_exists($key, $this->data);
    }

    public function remove($key) {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
        return true;
    }

    public function clear() {
        return ($this->data = []);
    }

    public function isEmpty() {
        return empty($this->data);
    }

    public function offsetExists($key) {
        return $this->has($key);
    }

    public function offsetGet($key) {
        return $this->get($key);
    }

    public function offsetSet($key, $value) {
        return $this->set($key, $value);
    }

    public function offsetUnset($key) {
        return $this->remove($key);
    }

    public function count() {
        return count($this->data);
    }

    public function getIterator() {
        return new ArrayIterator($this->data);
    }

}
