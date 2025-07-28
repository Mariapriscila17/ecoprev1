<?php
// Iniciar sessão
session_start();

// SUAS configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'cadastro');      // Nome do seu banco
define('DB_USER', 'root');          // Usuário padrão do XAMPP
define('DB_PASS', '');              // Senha vazia (padrão do XAMPP)

// Função para conectar ao banco de dados
function conectarBanco() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $opcoes = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $opcoes);
    } catch (PDOException $e) {
        die("Erro na conexão com o banco: " . $e->getMessage());
    }
}

// Função para criar hash da senha
function hashSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');
?>