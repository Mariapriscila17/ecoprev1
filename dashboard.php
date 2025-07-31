<?php
// Conexão com banco
$conn = new mysqli("localhost", "root", "", "cadastro", 49170);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta os registros
$sql = "SELECT * FROM registro ORDER BY data_registro DESC";
$result = $conn->query($sql);

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>EcoPrev - Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #f4f6f8;
    }
    h1 {
      color: #0a9396;
      text-align: center;
    }
    .stats {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
    }
    .stat-card {
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 15px 20px;
      width: 200px;
      text-align: center;
      box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
    }
    .chart-container {
      margin: 30px auto;
      max-width: 600px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 1px 1px 6px rgba(0,0,0,0.1);
    }
    .recent-records {
      max-width: 600px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
    }
    .record-item {
      padding: 8px 0;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
    }
  </style>
</head>
<body>
  <h1>EcoPrev - Dashboard</h1>

  <div class="stats">
    <div class="stat-card">
      <div id="total-registros">0</div>
      <div>Total de Registros</div>
    </div>
    <div class="stat-card">
      <div id="total-peso">0 kg</div>
      <div>Peso Total</div>
    </div>
    <div class="stat-card">
      <div id="tipo-mais-comum">-</div>
      <div>Tipo Mais Comum</div>
    </div>
    <div class="stat-card">
      <div id="setor-mais-ativo">-</div>
      <div>Setor Mais Ativo</div>
    </div>
  </div>

  <div class="chart-container">
    <h3>Distribuição por Tipo de Resíduo</h3>
    <canvas id="graficoPizza"></canvas>
  </div>

  <div class="chart-container">
    <h3>Resíduos por Setor</h3>
    <canvas id="graficoSetor"></canvas>
  </div>

  <div class="recent-records">
    <h3>Registros Recentes</h3>
    <div id="registros-recentes"></div>
  </div>

  <script>
    const registros = <?php echo json_encode($registros); ?>;
    console.log("Registros carregados:", registros);

    function atualizarDashboard() {
      document.getElementById('total-registros').textContent = registros.length;

      const pesoTotal = registros.reduce((total, r) => total + parseFloat(r.quantidade), 0);
      document.getElementById('total-peso').textContent = pesoTotal.toFixed(1) + ' kg';

      const tipoCount = {};
      const setorCount = {};
      const dadosTipo = {};
      const dadosSetor = {};

      registros.forEach(r => {
        tipoCount[r.tipo] = (tipoCount[r.tipo] || 0) + 1;
        setorCount[r.setor] = (setorCount[r.setor] || 0) + 1;
        dadosTipo[r.tipo] = (dadosTipo[r.tipo] || 0) + parseFloat(r.quantidade);
        dadosSetor[r.setor] = (dadosSetor[r.setor] || 0) + parseFloat(r.quantidade);
      });

      const tipoMaisComum = Object.keys(tipoCount).reduce((a, b) => tipoCount[a] > tipoCount[b] ? a : b, '-');
      const setorMaisAtivo = Object.keys(setorCount).reduce((a, b) => setorCount[a] > setorCount[b] ? a : b, '-');

      document.getElementById('tipo-mais-comum').textContent = tipoMaisComum;
      document.getElementById('setor-mais-ativo').textContent = setorMaisAtivo;

      new Chart(document.getElementById('graficoPizza'), {
        type: 'pie',
        data: {
          labels: Object.keys(dadosTipo),
          datasets: [{
            data: Object.values(dadosTipo),
            backgroundColor: ['#FF6B35', '#F7931E', '#FFD23F', '#06D6A0']
          }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
      });

      new Chart(document.getElementById('graficoSetor'), {
        type: 'doughnut',
        data: {
          labels: Object.keys(dadosSetor),
          datasets: [{
            data: Object.values(dadosSetor),
            backgroundColor: ['#1E3C72', '#2A5298', '#FF6B35']
          }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
      });

      const container = document.getElementById('registros-recentes');
      container.innerHTML = '';
      const recentes = registros.slice(0, 5);
      recentes.forEach(r => {
        const div = document.createElement('div');
        div.className = 'record-item';
        div.innerHTML = `<span><strong>${r.tipo}</strong> - ${r.setor} (${r.area})</span><span>${r.quantidade} kg</span>`;
        container.appendChild(div);
      });
    }

    atualizarDashboard();
  </script>
</body>
</html>
