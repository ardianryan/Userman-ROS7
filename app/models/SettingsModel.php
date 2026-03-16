<?php

class SettingsModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function get($key, $default = '') {
        $this->db->query("SELECT setting_value FROM settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $row = $this->db->single();
        return $row ? $row['setting_value'] : $default;
    }

    public function getAll() {
        $this->db->query("SELECT setting_key, setting_value FROM settings");
        $rows = $this->db->resultSet();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function set($key, $value) {
        $this->db->query("INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
                          ON DUPLICATE KEY UPDATE setting_value = :value2");
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        $this->db->bind(':value2', $value);
        $this->db->execute();
    }

    public function setMany(array $data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }
}
