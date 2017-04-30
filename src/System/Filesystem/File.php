<?php

namespace Articstudio\Bitbucket\System\Filesystem;

class File {

    protected $filename;
    protected $path;
    protected $realpath;

    public function __construct($path) {
        $this->realpath = realpath($path);
    }

    public static function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public function readCSV($delimiter = ',') {
        $csv = [];
        if (($handle = fopen($this->realpath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 4096, $delimiter)) !== false) {
                $csv[] = $data;
            }
            fclose($handle);
        }
        return $csv;
    }

    public static function base64_to_jpeg($base64_string, $directory, $filename) {
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        if (is_dir($directory)) {
            $path = $directory . DIRECTORY_SEPARATOR . $filename;
            return static::save($path, base64_decode($base64_string));
        }
        return false;
    }

    public static function save($path, $content) {
        if ($ifp = fopen($path, 'wb')) {
            $done = fwrite($ifp, $content);
            fclose($ifp);
            return ($done !== false);
        }
        return false;
    }

    public static function unlink($path) {
        $realpath = realpath($path);
        if ($realpath && is_file($realpath)) {
            return unlink($realpath);
        }
        return null;
    }

    public static function copy($source, $path) {
        $realpath = realpath($source);
        if ($realpath && is_file($realpath)) {
            return copy($realpath, $path);
        }
        return null;
    }

    public static function rename($path) {
        $realpath = realpath($path);
        if ($realpath && is_file($realpath)) {
            return rename($realpath);
        }
        return null;
    }

}
