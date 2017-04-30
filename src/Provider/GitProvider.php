<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;
use Articstudio\Bitbucket\System\Git;

class GitProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['git'] = function($c) {
            return new Git($c->settings->get('git_bin'), $c->settings->get('repositories_dir'));
        };
    }

}
