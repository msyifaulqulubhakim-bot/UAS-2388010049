<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}
