<?php

namespace Articstudio\Bitbucket\Middleware;

use Articstudio\Bitbucket\ContainerAwareTrait;
use Pimple\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Articstudio\Bitbucket\Collection;
use Articstudio\Bitbucket\Deploy;
use Articstudio\Bitbucket\Exception\Deploy\NotFoundException;

class Kernel {

    use ContainerAwareTrait;

    protected $container;
    private $results = [];

    public function __construct(Container $container) {
        $this->setContainer($container);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
        $this->createRepositoryData();
        $this->applyChanges();
        foreach ($this->results as $result) {
            $response->getBody()->write($result);
        }
        return $response;
    }

    private function createRepositoryData() {
        $repository_name = $this->container->payload->get('repository')->get('full_name');
        $repository_data = $this->container->repositories->get($repository_name);
        $this->container['repository'] = new Collection([
            'name' => $repository_name,
            'dir' => $this->container->git->getRepositoryDirectory($repository_name),
            'branches' => new Collection($repository_data)
        ]);
    }

    private function applyChanges() {
        $changes = $this->container->payload->get('push')->get('changes')->getIterator();
        foreach ($changes as $change) {
            $deploy = new Deploy($this->container, $change);
            try {
                $this->results[] = $deploy->run();
            } catch (NotFoundException $exception) {
                $this->container->logger->info($exception->getMessage(), $change);
            }
        }
    }

}
