<?php
// Exemplo: checar se o usuário está logado, se quiser
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header('Location: login.php');
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EcoPrev - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <div class="background-overlay"></div>
    
    <header>
        <div class="logo">ECOPREV</div>
    </header>

    <div class="container">
        <div id="dashboard" class="page active">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="total-registros">0</div>
                    <div class="stat-label">Total de Registros</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="total-peso">0 kg</div>
                    <div class="stat-label">Peso Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="tipo-mais-comum">-</div>
                    <div class="stat-label">Tipo Mais Comum</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="setor-mais-ativo">-</div>
                    <div class="stat-label">Setor Mais Ativo</div>
                </div>
            </div>

            <div class="dashboard-container">
                <div class="chart-container">
                    <h3>Distribuição por Tipo de Resíduo</h3>
                    <canvas id="graficoPizza"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Resíduos por Setor</h3>
                    <canvas id="graficoSetor"></canvas>
                </div>
            </div>

            <div class="recent-records">
                <h3>Registros Recentes</h3>
                <div id="registros-recentes"></div>
            </div>
        </div>
    </div>

    <script>
        function atualizarDashboard() {
            const registros = JSON.parse(localStorage.getItem('registros') || '[]');

            document.getElementById('total-registros').textContent = registros.length;

            const pesoTotal = registros.reduce((total, r) => total + r.quantidade, 0);
            document.getElementById('total-peso').textContent = pesoTotal.toFixed(1) + ' kg';

            const tipoCount = {};
            registros.forEach(r => {
                tipoCount[r.tipo] = (tipoCount[r.tipo] || 0) + 1;
            });
            const tipoMaisComum = Object.keys(tipoCount).reduce((a, b) => tipoCount[a] > tipoCount[b] ? a : b, '-');
            document.getElementById('tipo-mais-comum').textContent = tipoMaisComum;

            const setorCount = {};
            registros.forEach(r => {
                setorCount[r.setor] = (setorCount[r.setor] || 0) + 1;
            });
            const setorMaisAtivo = Object.keys(setorCount).reduce((a, b) => setorCount[a] > setorCount[b] ? a : b, '-');
            document.getElementById('setor-mais-ativo').textContent = setorMaisAtivo;

            const dadosTipo = {};
            registros.forEach(r => {
                dadosTipo[r.tipo] = (dadosTipo[r.tipo] || 0) + r.quantidade;
            });

            new Chart(document.getElementById('graficoPizza').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: Object.keys(dadosTipo),
                    datasets: [{
                        data: Object.values(dadosTipo),
                        backgroundColor: ['#FF6B35', '#F7931E', '#FFD23F', '#06D6A0', '#118AB2', '#073B4C', '#9B59B6', '#E74C3C']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            const dadosSetor = {};
            registros.forEach(r => {
                dadosSetor[r.setor] = (dadosSetor[r.setor] || 0) + r.quantidade;
            });

            new Chart(document.getElementById('graficoSetor').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(dadosSetor),
                    datasets: [{
                        data: Object.values(dadosSetor),
                        backgroundColor: ['#1E3C72', '#2A5298', '#FF6B35', '#F7931E']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            const container = document.getElementById('registros-recentes');
            container.innerHTML = '';
            const registrosRecentes = registros.slice(-5).reverse();
            registrosRecentes.forEach(r => {
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
