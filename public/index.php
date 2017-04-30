<?php

/* ----------------------------------------------------------------------------
 * Autoload
 */

require_once '../vendor/autoload.php';

/* ----------------------------------------------------------------------------
 * Config
 */

$config = require dirname(__DIR__) . '/config/app.php';

/* ----------------------------------------------------------------------------
 * Application
 */

$app = new \Articstudio\Bitbucket\App($config);
$app->run();
