<?php
require_once 'config.php';

// Processar cadastro se form foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $matricula = trim($_POST['matricula']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);
    
    $erros = [];
    
    // Validações
    if (empty($nome)) $erros[] = "Nome é obrigatório";
    if (empty($matricula)) $erros[] = "Matrícula é obrigatória";
    if (empty($email)) $erros[] = "Email é obrigatório";
    if (empty($senha)) $erros[] = "Senha é obrigatória";
    if ($senha !== $confirmar_senha) $erros[] = "Senhas não coincidem";
    
    // Validações específicas da senha
    if (strlen($senha) < 8) $erros[] = "A senha deve ter pelo menos 8 caracteres";
    if (!preg_match('/[0-9]/', $senha)) $erros[] = "A senha deve conter pelo menos um número";
    if (!preg_match('/[A-Z]/', $senha)) $erros[] = "A senha deve conter pelo menos uma letra maiúscula";
    if (!preg_match('/[a-z]/', $senha)) $erros[] = "A senha deve conter pelo menos uma letra minúscula";
    if (!preg_match('/[!@#$%&?]/', $senha)) $erros[] = "A senha deve conter pelo menos um caractere especial: ! @ # $ % & ?";
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido";
    
    if (empty($erros)) {
        try {
            $pdo = conectarBanco();
            
            // Verificar se matrícula ou email já existem
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE matricula = ? OR email = ?");
            $stmt->execute([$matricula, $email]);
            
            if ($stmt->fetchColumn() > 0) {
                $erros[] = "Matrícula ou email já cadastrados";
            } else {
                // Inserir novo usuário
                $senha_hash = hashSenha($senha);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, matricula, email, senha) VALUES (?, ?, ?, ?)");
                
                if ($stmt->execute([$nome, $matricula, $email, $senha_hash])) {
                    $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça seu login.";
                    header('Location: index.html');
                    exit;
                }
            }
        } catch (PDOException $e) {
            $erros[] = "Erro no sistema. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPreve - Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <svg class="logo-image pulse-effect" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="blueGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#1e3a8a;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="yellowGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#f59e0b;stop-opacity:1" />
                    </linearGradient>
                </defs>
                
                <circle cx="200" cy="80" r="65" fill="none" stroke="url(#yellowGradient)" stroke-width="8"/>
                <path d="M160 80 Q170 40 220 50 Q240 60 230 90 Q220 110 190 105 Q160 100 160 80" fill="url(#blueGradient)"/>
                <path d="M200 85 Q230 70 250 90 Q260 110 240 120 Q220 125 210 110 Q200 95 200 85" fill="url(#yellowGradient)"/>
                <text x="50" y="200" font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="url(#blueGradient)">Eco</text>
                <text x="160" y="200" font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="url(#yellowGradient)">Preve</text>
            </svg>
        </div>

        <div class="card login-card">
            <h2 class="card-title">Criar Conta</h2>
            
            <?php if (!empty($erros)): ?>
                <div class="error-message">
                    <?php foreach ($erros as $erro): ?>
                        <p><?php echo htmlspecialchars($erro); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" onsubmit="return validateForm(event)">
                <div class="form-group">
                    <label class="form-label" for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="form-input" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="matricula">Matrícula</label>
                    <input type="text" id="matricula" name="matricula" class="form-input" value="<?php echo isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-input" required>
                    <div class="password-requirements">
                        <h3>A senha deve conter:</h3>
                        <ul>
                            <li id="req-length">8 caracteres.</li>
                            <li id="req-number">Pelo menos um número.</li>
                            <li id="req-uppercase">Pelo menos uma letra maiúscula.</li>
                            <li id="req-lowercase">Pelo menos uma letra minúscula.</li>
                            <li id="req-special">Pelo menos um desses caracteres especiais: <strong>! @ # $ % & ?</strong></li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-login">Cadastrar</button>
                
                <div class="back-link">
                    <a href="index.html">← Voltar ao Login</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>