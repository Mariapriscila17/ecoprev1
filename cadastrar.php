<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha

    // Conectar ao banco
    $banco = new BancoDeDados();
    $conexao = $banco->obterConexao();

    if ($conexao) {
        try {
            // Verificar se já existe matrícula ou email
            $sqlVerifica = "SELECT COUNT(*) FROM usuario WHERE matricula = :matricula OR email = :email";
            $stmtVerifica = $conexao->prepare($sqlVerifica);
            $stmtVerifica->bindParam(':matricula', $matricula);
            $stmtVerifica->bindParam(':email', $email);
            $stmtVerifica->execute();

            if ($stmtVerifica->fetchColumn() > 0) {
                echo "Erro: matrícula ou e-mail já cadastrados.";
                exit;
            }

            // Inserir no banco
            $sql = "INSERT INTO usuario (nome, matricula, email, senha) 
                    VALUES (:nome, :matricula, :email, :senha)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            if ($stmt->execute()) {
                echo "Cadastro realizado com sucesso!";
            } else {
                echo "Erro ao cadastrar.";
            }

        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
?>

