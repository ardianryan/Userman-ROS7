<?php

class RouterModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllRouters() {
        $this->db->query("SELECT * FROM routers ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getRouterById($id) {
        $this->db->query("SELECT * FROM routers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addRouter($data) {
        $this->db->query("INSERT INTO routers (name, ip_address, api_user, api_pass, secret) VALUES (:name, :ip_address, :api_user, :api_pass, :secret)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':ip_address', $data['ip_address']);
        $this->db->bind(':api_user', $data['api_user']);
        $this->db->bind(':api_pass', $data['api_pass']);
        $this->db->bind(':secret', $data['secret']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateRouter($data) {
        $this->db->query("UPDATE routers SET name = :name, ip_address = :ip_address, api_user = :api_user, api_pass = :api_pass, secret = :secret WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':ip_address', $data['ip_address']);
        $this->db->bind(':api_user', $data['api_user']);
        $this->db->bind(':api_pass', $data['api_pass']);
        $this->db->bind(':secret', $data['secret']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteRouter($id) {
        $this->db->query("DELETE FROM routers WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
