<?php
class BancoDeDados {
    private $host = "localhost:49160";
    private $nome_banco = "cadastro";
    private $usuario = "root";
    private $senha = "";
    public $conexao;

    public function obterConexao() {
        try {
            $this->conexao = new PDO(
                "mysql:host={$this->host};dbname={$this->nome_banco};charset=utf8",
                $this->usuario,
                $this->senha
            );
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexao;
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco: " . $e->getMessage();
            return null;
        }
    }
}

function hashSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}
?>
