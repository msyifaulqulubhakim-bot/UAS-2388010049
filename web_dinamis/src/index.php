<?php
session_start();

// Autoload config and classes
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Article.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ArticleController.php';

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'home':
        $controller = new ArticleController();
        $controller->index();
        break;
        
    case 'article':
        $controller = new ArticleController();
        $controller->view();
        break;
        
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case 'admin':
        $controller = new ArticleController();
        $controller->admin();
        break;
        
    case 'create':
        $controller = new ArticleController();
        $controller->create();
        break;
        
    case 'edit':
        $controller = new ArticleController();
        $controller->edit();
        break;
        
    case 'delete':
        $controller = new ArticleController();
        $controller->delete();
        break;
        
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Halaman tidak ditemukan.";
        break;
}