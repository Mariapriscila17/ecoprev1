<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
       
</head>
<body>
    <form action="cadastrar.php" method="post">
        <h2>Formulário de Cadastro</h2>

        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Matrícula:</label>
        <input type="text" name="matricula" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
