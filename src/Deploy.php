<?php

namespace Articstudio\Bitbucket;

use Articstudio\Bitbucket\ContainerAwareTrait;
use Pimple\Container;
use Articstudio\Bitbucket\Change;
use Articstudio\Bitbucket\Exception\Deploy\NotFoundException;
use Articstudio\Bitbucket\System\Directory;
use RuntimeException;
use Monolog\Logger;
use Monolog\Handler\NativeMailerHandler;

class Deploy {

    use ContainerAwareTrait;

    private $change;
    private $branch_name;
    private $branch;
    private $repository_name;
    private $git;
    private $branch_dir;
    private $logs = [];
    private $logger;

    public function __construct(Container $container, Change $change) {
        $this->setContainer($container);
        $this->change = $change;
    }

    public function run() {
        return $this->parse()
                        ->init()
                        ->checkRepositoryDir()
                        ->fetchRepository()
                        ->validateDirectories()
                        ->checkoutRepository()
                        ->execCommands()
                        ->debug();
    }

    private function parse() {
        $this->branch_name = $this->change->get('new')->get('name');
        $this->repository_name = $this->container->repository->get('name');
        $this->repository_dir = $this->container->repository->get('dir');
        $this->git = $this->container->git;
        if (!$this->container->repository->get('branches')->has($this->branch_name)) {
            throw new NotFoundException(sprintf('Branch "%s" not found in "%s"', $this->branch_name, $this->repository_name));
        }
        $this->branch = $this->container->repository->get('branches')->get($this->branch_name);
        $this->branch_dir = isset($this->branch['dir']) ? $this->branch['dir'] : false;
        return $this;
    }

    private function init() {
        $logger_name = $this->container->settings->get('logs_name', 'as-bitbucket') . '::' . $this->repository_name . '::' . $this->branch_name;
        $this->logger = new Logger($logger_name);

        $debug_to = isset($this->branch['debug']) ? $this->branch['debug'] : false;
        $debug_subject = $this->container->settings->get('debug_subject', false);
        $debug_from = $this->container->settings->get('debug_from', false);
        if ($debug_to && $debug_subject && $debug_from) {
            $this->logger->pushHandler(new NativeMailerHandler($debug_to, $debug_subject, $debug_from, Logger::DEBUG));
        }

        return $this;
    }

    private function checkRepositoryDir() {
        if (!realpath($this->repository_dir) && !$this->git->doClone($this->repository_name)) {
            throw new RuntimeException(sprintf('Repository clone "%s" not exists on "%s"', $this->repository_name, $this->repository_dir));
        }
        return $this;
    }

    private function fetchRepository() {
        if (!$this->git->doFetch($this->repository_name)) {
            throw new RuntimeException(sprintf('Repository fetch "%s" fail on "%s"', $this->repository_name, $this->repository_dir));
        }
        return $this;
    }

    private function validateDirectories() {
        $this->repository_dir = realpath($this->repository_dir);
        if (!$this->repository_dir) {
            throw new RuntimeException(sprintf('Repository directory "%s" not exists on "%s"', $this->repository_name, $this->repository_dir));
        }
        $dir = realpath($this->branch_dir);
        if (!$dir && !Directory::Make($this->branch_dir, 0755, true)) {
            throw new RuntimeException(sprintf('Repository branch directory "%s"::"%s" not exists on "%s"', $this->repository_name, $this->branch_name, $this->branch_dir));
        }
        $this->branch_dir = realpath($this->branch_dir);
        return $this;
    }

    private function checkoutRepository() {
        if (!$this->git->doCheckout($this->repository_name, $this->branch_name, $this->branch_dir)) {
            throw new RuntimeException(sprintf('Repository checkout "%s"::"%s" fail on "%s"', $this->repository_name, $this->branch_name, $this->branch_dir));
        }
        return $this;
    }

    private function execCommands() {
        $cmds = isset($this->branch['cmd']) ? $this->branch['cmd'] : [];
        foreach ($cmds as $key => $cmd) {
            $this->logs[] = '#' . $key . ' > ' . $cmd . ' => ' . shell_exec('cd ' . $this->branch_dir . ' && ' . $cmd);
        }
        return $this;
    }

    private function debug() {
        $logs = array_merge([sprintf('Deploy "%s"::"%s" on "%s" => SUCCESSFULLY', $this->repository_name, $this->branch_name, $this->branch_dir)], $this->logs);
        $message = sprintf('Deploy "%s"::"%s" / SUCCESSFULLY', $this->repository_name, $this->branch_name);
        $this->container->logger->debug($message, $logs);
        $this->logger->debug($message, $logs);
        return $message . "\n\n" . implode("\n", $logs);
    }

}
