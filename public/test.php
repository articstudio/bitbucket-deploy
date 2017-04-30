<?php

function __debug($title, $debug) {
    echo '<h3>' . $title . '</h3>';
    echo '<pre>' . print_r($debug, true) . '</pre>';
}

try {
    $a = new FakeClass;
} catch (Exception $e) {
    __debug('Exception', $e);
} catch (Throwable $e) {
    __debug('Throwable', $e);
}




