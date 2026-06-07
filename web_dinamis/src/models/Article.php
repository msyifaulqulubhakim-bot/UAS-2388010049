<?php
class Article {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM articles ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($title, $content, $excerpt, $mountain) {
        $stmt = $this->db->prepare("INSERT INTO articles (title, content, excerpt, mountain) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $content, $excerpt, $mountain]);
    }

    public function update($id, $title, $content, $excerpt, $mountain) {
        $stmt = $this->db->prepare("UPDATE articles SET title = ?, content = ?, excerpt = ?, mountain = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $excerpt, $mountain, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
