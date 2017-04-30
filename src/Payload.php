<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\Collection;
use Articstudio\Bitbucket\Change;
use DomainException;

class Payload extends Collection {

    public function __construct(array $items = []) {
        parent::__construct($items);
        $this->parse();
    }

    public function parse() {
        $this->set('repository', new Collection($this->get('repository', [])));
        $this->set('actor', new Collection($this->get('actor', [])));
        $push = new Collection($this->get('push', []));
        $changes = $push->get('changes', []);
        foreach ($changes as $key => $change) {
            $changes[$key] = new Change($change);
        }
        $push->set('changes', new Collection($changes));
        $this->set('push', $push);
    }

    public function validate() {
        if ($this->isEmpty()) {
            throw new DomainException('Empty payload');
        }
        if ($this->get('repository')->isEmpty() || !$this->get('repository')->has('full_name')) {
            throw new DomainException('Incomplete payload repository data');
        }
        if ($this->get('push')->get('changes')->isEmpty()) {
            throw new DomainException('Incomplete payload push changes');
        }
        $changes = $this->get('push')->get('changes')->getIterator();
        foreach ($changes as $change) {
            $change->validate();
        }
    }

}
