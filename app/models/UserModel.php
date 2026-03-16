<?php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT * FROM users WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllUsers() {
        $this->db->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function createUser($data) {
        $this->db->query("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':role',     $data['role'] ?? 'admin');
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateUser($data) {
        $this->db->query("UPDATE users SET username = :username, role = :role WHERE id = :id");
        $this->db->bind(':id',       $data['id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':role',     $data['role']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updatePassword($id, $newPassword) {
        $this->db->query("UPDATE users SET password = :password WHERE id = :id");
        $this->db->bind(':id',       $id);
        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function usernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $this->db->query("SELECT id FROM users WHERE username = :username AND id != :id");
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query("SELECT id FROM users WHERE username = :username");
        }
        $this->db->bind(':username', $username);
        return $this->db->single() ? true : false;
    }
}
