<?php

namespace Articstudio\Bitbucket\System;

class Git {

    private $bin;
    private $dir;

    public function __construct($bin, $repositories_dir) {
        $this->bin = $bin;
        $this->dir = $repositories_dir;
    }

    public function getRepositoryDirectory($repository_name) {
        return $this->dir . '/' . $repository_name;
    }

    public function doClone($repository_name) {
        $cmd = 'cd ' . $this->dir . ' && ' . $this->bin . ' clone --mirror git@bitbucket.org:' . $repository_name . '.git ' . $repository_name;
        return (($this->exec($cmd) === 0) && is_dir($this->getRepositoryDirectory($repository_name)));
    }

    public function doFetch($repository_name) {
        $cmd = 'cd ' . $this->getRepositoryDirectory($repository_name) . ' && ' . $this->bin . ' fetch';
        return ($this->exec($cmd) === 0);
    }

    public function doCheckout($repository_name, $branch_name, $branch_dir) {
        $cmd = 'cd ' . $this->getRepositoryDirectory($repository_name) . ' && GIT_WORK_TREE=' . $branch_dir . ' ' . $this->bin . ' checkout -f ' . $branch_name;
        return ($this->exec($cmd) === 0);
    }

    private function exec($cmd) {
        system($cmd, $status);
        return $status;
    }

}
