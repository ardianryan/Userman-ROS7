<?php

class Cache {
    private static $cacheDir = __DIR__ . '/../../app/cache';

    private static function init() {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }

    public static function set($key, $data, $ttl = 3600) {
        self::init();
        $filename = self::$cacheDir . '/' . md5($key) . '.cache';
        $cacheData = [
            'expires' => time() + $ttl,
            'data' => $data
        ];
        return file_put_contents($filename, serialize($cacheData)) !== false;
    }

    public static function get($key) {
        $filename = self::$cacheDir . '/' . md5($key) . '.cache';
        if (!file_exists($filename)) {
            return null;
        }

        $content = file_get_contents($filename);
        $cacheData = unserialize($content);

        if (time() > $cacheData['expires']) {
            unlink($filename);
            return null;
        }

        return $cacheData['data'];
    }

    public static function delete($key) {
        $filename = self::$cacheDir . '/' . md5($key) . '.cache';
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return false;
    }

    public static function clear() {
        if (!is_dir(self::$cacheDir)) return;
        $files = glob(self::$cacheDir . '/*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
