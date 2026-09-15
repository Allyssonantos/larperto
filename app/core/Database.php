<?php
/**
 * Classe de conexão com o banco de dados (Singleton + PDO)
 */

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require APP_PATH . '/config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            if (ENVIRONMENT === 'development') {
                die('Erro na conexão com o banco de dados: ' . $e->getMessage());
            } else {
                die('Erro na conexão com o banco de dados.');
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    // Impede clonagem
    private function __clone() {}
}