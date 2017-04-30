<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\Collection;
use DomainException;

class Change extends Collection {

    public function __construct(array $items = []) {
        parent::__construct($items);
        $this->parse();
    }

    public function parse() {
        $this->set('new', new Collection($this->get('new', [])));
    }

    public function validate() {
        if ($this->get('new')->isEmpty() || !$this->get('new')->has('type') || !$this->get('new')->has('name')) {
            throw new DomainException('Incomplete payload push change');
        }
    }

}
