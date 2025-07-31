<?php 
class BancoDeDados {
    private $host = "localhost";
    private $porta = "49170"; // Porta correta do MySQL
    private $nome_banco = "cadastro";
    private $usuario = "root";
    private $senha = "";
    public $conexao;

    public function obterConexao() {
        $this->conexao = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->porta};dbname={$this->nome_banco};charset=utf8";
            $this->conexao = new PDO($dsn, $this->usuario, $this->senha);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $excecao) {
            echo "Erro de conexão: " . $excecao->getMessage();
            return null;
        }

        return $this->conexao;
    }
}
?>
