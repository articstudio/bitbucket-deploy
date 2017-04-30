<?php

namespace Articstudio\Bitbucket\System;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Directory {

    public static function Make($pathname, $mode = 0777, $recursive = false, $context = null) {
        if (false !== file_exists($pathname)) {
            return false;
        }
        return mkdir($pathname, $mode, $recursive, $context);
    }

    public static function Remove($dirname, $context = null) {
        if (false === file_exists($dirname)) {
            return false;
        }
        return rmdir($dirname, $context);
    }

    public static function RemoveRecursive($path) {
        if (false === file_exists($path)) {
            return false;
        }
        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $fileinfo) {
            if ($fileinfo->isDir()) {
                if (false === rmdir($fileinfo->getRealPath())) {
                    return false;
                }
            } else {
                if (false === unlink($fileinfo->getRealPath())) {
                    return false;
                }
            }
        }
        return rmdir($path);
    }

}
