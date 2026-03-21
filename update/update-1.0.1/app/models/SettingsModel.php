<?php

class SettingsModel {
    private $db;
    private $cacheKey = 'app_settings';

    public function __construct() {
        $this->db = new Database;
    }

    public function get($key, $default = '') {
        $settings = $this->getAll();
        return $settings[$key] ?? $default;
    }

    public function getAll() {
        // Try to get from cache first
        $cached = Cache::get($this->cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // If not in cache, get from DB
        $this->db->query("SELECT setting_key, setting_value FROM settings");
        $rows = $this->db->resultSet();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        // Store in cache for 1 hour
        Cache::set($this->cacheKey, $settings, 3600);

        return $settings;
    }

    public function set($key, $value) {
        $this->db->query("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
                          ON DUPLICATE KEY UPDATE setting_value = :value2");
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        $this->db->bind(':value2', $value);
        $this->db->execute();

        // Invalidate cache
        Cache::delete($this->cacheKey);
    }

    public function setMany(array $data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }
}
