<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\Collection;
use DomainException;

class Header extends Collection {

    const EVENT_PUSH = 'repo:push';
    const USER_AGENT = 'Bitbucket-Webhooks/2.0';

    public function validate() {
        if ($this->get('event') !== self::EVENT_PUSH) {
            throw new DomainException(sprintf('Invalid event "%s"', $this->get('event')));
        }
        if ($this->get('user_agent') !== self::USER_AGENT) {
            throw new DomainException(sprintf('Invalid User Agent "%s"', $this->get('user_agent')));
        }
    }

}
