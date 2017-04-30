<?php

namespace Articstudio\Bitbucket\System\Filesystem;

class Disk {

    const RAW_OUTPUT = true;

    private $path;

    function __construct($path = '/') {
        $this->path = $path;
    }

    public function totalSpace($rawOutput = false) {
        $diskTotalSpace = @disk_total_space($this->path);

        if ($diskTotalSpace === FALSE) {
            throw new \Exception('totalSpace(): Invalid disk path.');
        }

        return $rawOutput ? $diskTotalSpace : $this->addUnits($diskTotalSpace);
    }

    public function freeSpace($rawOutput = false) {
        $diskFreeSpace = @disk_free_space($this->path);

        if ($diskFreeSpace === FALSE) {
            throw new \Exception('freeSpace(): Invalid disk path.');
        }

        return $rawOutput ? $diskFreeSpace : $this->addUnits($diskFreeSpace);
    }

    public function freeSpaceAVG($precision = 2) {
        try {
            return round((($this->freeSpace(self::RAW_OUTPUT - 100) / $this->totalSpace(self::RAW_OUTPUT)) * 100), $precision);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function usedSpace($rawOutput = false) {
        $diskFreeSpace = @disk_free_space($this->path);
        $diskTotalSpace = @disk_total_space($this->path);

        $diskUsedSpace = $diskTotalSpace - $diskFreeSpace;

        return $rawOutput ? $diskUsedSpace : $this->addUnits($diskUsedSpace);
    }

    public function usedSpaceAVG($precision = 2) {
        try {
            return round((100 - ($this->freeSpace(self::RAW_OUTPUT) / $this->totalSpace(self::RAW_OUTPUT)) * 100), $precision);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getDiskPath() {
        return $this->path;
    }

    private function addUnits($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    public static function stats($path = '/') {
        $disk = new static($path);
        return [
            'total' => [
                'raw' => $disk->totalSpace(true),
                'format' => $disk->totalSpace()
            ],
            'free' => [
                'raw' => $disk->freeSpace(true),
                'format' => $disk->freeSpace(),
                'avg' => $disk->freeSpaceAVG()
            ],
            'used' => [
                'raw' => $disk->usedSpace(true),
                'format' => $disk->usedSpace(),
                'avg' => $disk->usedSpaceAVG()
            ]
        ];
    }

}
