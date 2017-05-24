<?php

namespace Articstudio\Bitbucket\System\Filesystem;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Directory {

    public static function Make($pathname, $mode = 0777, $recursive = false) {
        if (false !== file_exists($pathname)) {
            return false;
        }
        return mkdir($pathname, $mode, $recursive);
    }

    public static function Remove($dirname) {
        if (false === file_exists($dirname)) {
            return false;
        }
        return rmdir($dirname);
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
