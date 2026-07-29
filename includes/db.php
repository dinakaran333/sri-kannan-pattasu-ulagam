<?php
/**
 * Database Connection Handler using PDO
 * Online Cracker Shop
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("<div style='padding:20px; font-family:sans-serif; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px; margin:20px;'>
                    <h2>Database Connection Error</h2>
                    <p>Unable to connect to MySQL database: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>
                    <p>Please ensure XAMPP MySQL service is running and the database <code>" . DB_NAME . "</code> has been imported from <code>database/cracker_shop.sql</code>.</p>
                 </div>");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Global PDO Connection Variable
$db = Database::getInstance()->getConnection();
