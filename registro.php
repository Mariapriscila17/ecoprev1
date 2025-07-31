<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coletando os dados do formulário
    $setor = $_POST['setor'] ?? '';
    $area = $_POST['area'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;
 
    // Verifica se todos os campos estão preenchidos
    if ($setor && $area && $tipo && is_numeric($quantidade) && $quantidade >= 0) {
        // Conexão com o banco
        $conn = new mysqli("localhost", "root", "", "cadastro",49170);
 
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }
 
        // Prepara e executa o INSERT
        $stmt = $conn->prepare("INSERT INTO registro (setor, area, tipo, quantidade, data_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssd", $setor, $area, $tipo, $quantidade);
 
        if ($stmt->execute()) {
            echo "<script>alert('Registro salvo com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao salvar o registro.');</script>";
        }
 
        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Preencha todos os campos corretamente.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Registro de Resíduos - EcoPrev</title>
  
  <!-- CSS interno -->
  <style>
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      color: #333;
      position: relative;
      min-height: 100vh;
      padding-bottom: 60px;
    }

    #bg-video {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
      z-index: -1;
      object-fit: cover;
      opacity: 0.15;
    }

    header.container {
      background-color: #0a9396;
      color: white;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      font-size: 2.5em;
    }

    nav {
      margin-top: 10px;
    }

    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .login-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .login-container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #0a9396;
    }

    form label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    form select,
    form input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #0a9396;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 1em;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #00777b;
    }

    footer {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: #0a9396;
      color: white;
      text-align: center;
      padding: 10px;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
  <video autoplay muted loop playsinline id="bg-video">
    <source src="video.mp4" type="video/mp4" />
    Seu navegador não suporta vídeo em background.
  </video>

  <header class="container">
    <h1>EcoPrev</h1>
    <nav>
      <a href="cadastro.php">Home</a>
      <a href="dashboard.php">Dashboard</a>
    </nav>
  </header>

  <main class="login-wrapper">
    <section class="login-container">
      <h2>Registrar Resíduo</h2>
      <form id="registro-form" method="POST" action="">
        <label for="setor">Setor:</label>
        <select id="setor" name="setor" required>
          <option value="">Selecione o setor</option>
          <option value="Administrativo">Administrativo</option>
          <option value="Produção">Produção</option>
          <option value="Logística">Logística</option>
        </select>

        <label for="area">Área:</label>
        <select id="area" name="area" required>
          <option value="">Selecione uma área</option>
          <option value="Orgânico">Orgânico</option>
        </select>

        <label for="tipo">Tipo de Resíduo:</label>
        <select id="tipo" name="tipo" required>
          <option value="">Tipo de Resíduo</option>
          <option value="Orgânico">Orgânico</option>
          <option value="Plástico">Plástico</option>
          <option value="Metal">Metal</option>
          <option value="Químico">Químico</option>
        </select>

        <label for="quantidade">Quantidade (kg):</label>
        <input type="number" id="quantidade" name="quantidade" min="0" required />

        <button type="submit">Salvar Registro</button>
      </form>
    </section>
  </main>

  <footer>
    &copy; 2025 EcoPrev. Todos os direitos reservados.
  </footer>

  <script src="script.js/logica.js"></script>
</body>
</html>
