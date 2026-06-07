<?php
class ArticleController {
    private $articleModel;

    public function __construct() {
        $this->articleModel = new Article();
    }

    public function index() {
        $articles = $this->articleModel->getAll();
        require_once 'views/articles/index.php';
    }

    public function view() {
        $id = $_GET['id'] ?? 0;
        $article = $this->articleModel->getById($id);
        if (!$article) {
            die('Artikel tidak ditemukan.');
        }
        require_once 'views/articles/view.php';
    }

    public function admin() {
        $this->checkAdmin();
        $articles = $this->articleModel->getAll();
        require_once 'views/articles/admin.php';
    }

    public function create() {
        $this->checkAdmin();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $mountain = trim($_POST['mountain'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($title && $mountain && $excerpt && $content) {
                $this->articleModel->create($title, $content, $excerpt, $mountain);
                header('Location: /?route=admin');
                exit;
            } else {
                $error = 'Semua field wajib diisi.';
            }
        }
        require_once 'views/articles/create.php';
    }

    public function edit() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? 0;
        $article = $this->articleModel->getById($id);
        if (!$article) {
            die('Artikel tidak ditemukan.');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $mountain = trim($_POST['mountain'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($title && $mountain && $excerpt && $content) {
                $this->articleModel->update($id, $title, $content, $excerpt, $mountain);
                header('Location: /?route=admin');
                exit;
            } else {
                $error = 'Semua field wajib diisi.';
            }
        }
        require_once 'views/articles/edit.php';
    }

    public function delete() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? 0;
        $this->articleModel->delete($id);
        header('Location: /?route=admin');
        exit;
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: /?route=login');
            exit;
        }
    }
}
