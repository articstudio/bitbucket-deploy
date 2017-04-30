<?php

namespace Articstudio\Bitbucket\Provider;

use Articstudio\Bitbucket\Provider\AbstractServiceProvider;
use Pimple\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\NativeMailerHandler;

class LoggerProvider extends AbstractServiceProvider {

    public function register(Container $container) {
        $container['logger'] = function($c) {
            $s = $c->settings;
            $logger = new Logger($s->get('logs_name', 'as-bitbucket'));

            $debug_to = $s->get('debug_to', false);
            $debug_subject = $s->get('debug_subject', false);
            $debug_from = $s->get('debug_from', false);
            if ($debug_to && $debug_subject && $debug_from) {
                $logger->pushHandler(new NativeMailerHandler($debug_to, $debug_subject, $debug_from, Logger::DEBUG));
            }

            $log_file = $s->get('logs_file', dirname(dirname(__DIR__)) . '/logs/app.log');
            $logger->pushHandler(new StreamHandler($log_file, Logger::INFO));

            $logger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::NOTICE));
            return $logger;
        };
    }

}
