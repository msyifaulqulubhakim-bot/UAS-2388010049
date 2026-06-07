<?php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $host = getenv('DATABASE_HOST') ?: (getenv('DB_HOST') ?: 'db');
            $dbname = getenv('DB_NAME') ?: 'app_db';
            $user = getenv('DB_USER') ?: 'appuser';
            $pass = getenv('DB_PASS') ?: 'apppassword';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new Exception("Koneksi database gagal: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
