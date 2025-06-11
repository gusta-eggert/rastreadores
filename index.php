    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'estoque_rastreadores';
    $conn = new mysqli($host, $user, $pass, $db);
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <title>Controle de Rastreadores</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    </head>
    <body class="bg-light p-4">

    <div class="container">
        <h2 class="mb-4">Movimentações de Rastreadores</h2>

        <!-- Botões principais -->
        <div class="mb-4">
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalCadastrarModelo">Cadastrar Modelo</button>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalCadastrarRastreador">Cadastrar Rastreador</button>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarSaida">Registrar Saída</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarVolta">Registrar Volta</button>
        </div>

        <!-- Tabela dos equipamentos -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Patrimônio</th>
                    <th>IMEI</th>
                    <th>Modelo</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $equipamentos = $conn->query("
                    SELECT r.patrimonio, r.imei, m.nome AS modelo_nome, r.situacao
                    FROM rastreadores r
                    LEFT JOIN modelos m ON r.id_modelo = m.id
                ");
                while ($eq = $equipamentos->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($eq['patrimonio']) ?></td>
                        <td><?= htmlspecialchars($eq['imei']) ?></td>
                        <td><?= htmlspecialchars($eq['modelo_nome']) ?></td>
                        <td><?= htmlspecialchars($eq['situacao']) ?></td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="abrirHistorico('<?= $eq['imei'] ?>', '<?= $eq['patrimonio'] ?>')"
                                    title="Visualizar histórico">
                                👁
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Histórico -->
    <div class="modal fade" id="modalHistorico" tabindex="-1" aria-labelledby="modalHistoricoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistoricoLabel">Histórico de Movimentações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="equipamentoInfo" class="mb-3"></h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Data de Saída</th>
                            <th>Data de Volta</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaHistoricoBody">
                        <!-- Conteúdo via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Cadastrar Modelo -->
    <div class="modal fade" id="modalCadastrarModelo" tabindex="-1" aria-labelledby="modalCadastrarModeloLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="cadastrar_modelo.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCadastrarModeloLabel">Cadastrar Modelo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nomeModelo" class="form-label">Nome do Modelo</label>
                    <input type="text" id="nomeModelo" name="nome_modelo" class="form-control" required />
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cadastrar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
    </div>

    <!-- Modal Cadastrar Rastreador -->
    <div class="modal fade" id="modalCadastrarRastreador" tabindex="-1" aria-labelledby="modalCadastrarRastreadorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="cadastrar_rastreador.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCadastrarRastreadorLabel">Cadastrar Rastreador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="patrimonio" class="form-label">Patrimônio</label>
                    <input type="text" id="patrimonio" name="patrimonio" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="imei" class="form-label">IMEI</label>
                    <input type="text" id="imei" name="imei" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="id_modelo" class="form-label">Modelo</label>
                    <select id="id_modelo" name="id_modelo" class="form-select" required>
                        <option value="" disabled selected>Selecione o modelo</option>
                        <?php
                        $modelos = $conn->query("SELECT id, nome FROM modelos ORDER BY nome");
                        while ($modelo = $modelos->fetch_assoc()):
                        ?>
                            <option value="<?= $modelo['id'] ?>"><?= htmlspecialchars($modelo['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Não tem campo situação, já fica "Disponível" no cadastro -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
    </div>

    <!-- Modal Registrar Saída -->
    <div class="modal fade" id="modalRegistrarSaida" tabindex="-1" aria-labelledby="modalRegistrarSaidaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="registrar_saida.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarSaidaLabel">Registrar Saída</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="imei_saida" class="form-label">IMEI</label>
                    <input list="imeiDisponiveis" id="imei_saida" name="imei" class="form-control" placeholder="Digite para filtrar..." required autocomplete="off" />
                    <datalist id="imeiDisponiveis">
                        <?php
                        // Listar só os rastreadores disponíveis para saída
                        $disponiveis = $conn->query("SELECT imei FROM rastreadores WHERE situacao = 'Disponível'");
                        while ($disp = $disponiveis->fetch_assoc()):
                        ?>
                            <option value="<?= $disp['imei'] ?>"></option>
                        <?php endwhile; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="cliente_saida" class="form-label">Cliente</label>
                    <input type="text" id="cliente_saida" name="cliente" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="data_saida" class="form-label">Data de Saída</label>
                    <input type="date" id="data_saida" name="data_saida" class="form-control" required />
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Registrar Saída</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
    </div>

    <!-- Modal Registrar Volta -->
    <div class="modal fade" id="modalRegistrarVolta" tabindex="-1" aria-labelledby="modalRegistrarVoltaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="registrar_volta.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarVoltaLabel">Registrar Volta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="imei_volta" class="form-label">IMEI</label>
                    <input list="imeiEmUso" id="imei_volta" name="imei" class="form-control" placeholder="Digite para filtrar..." required autocomplete="off" />
                    <datalist id="imeiEmUso">
                        <?php
                        // Listar só os rastreadores que estão "Em Cliente"
                        $em_uso = $conn->query("SELECT imei FROM rastreadores WHERE situacao = 'Em Cliente'");
                        while ($uso = $em_uso->fetch_assoc()):
                        ?>
                            <option value="<?= $uso['imei'] ?>"></option>
                        <?php endwhile; ?>
                    </datalist>
                </div>
                <div class="mb-3">
                    <label for="data_volta" class="form-label">Data de Volta</label>
                    <input type="date" id="data_volta" name="data_volta" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="motivo_volta" class="form-label">Motivo da Volta</label>
                    <textarea id="motivo_volta" name="motivo" class="form-control" rows="3" placeholder="Descreva o motivo da volta..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Registrar Volta</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function formatarDataBR(dataISO) {
    if (!dataISO) return '-';
    const [ano, mes, dia] = dataISO.split('-');
    return `${dia}/${mes}/${ano.slice(2)}`; // DD/MM/AA
    }

    function abrirHistorico(imei, patrimonio) {
        document.getElementById('equipamentoInfo').textContent = `Patrimônio: ${patrimonio} | IMEI: ${imei}`;
        const tbody = document.getElementById('tabelaHistoricoBody');
        tbody.innerHTML = '';

        fetch('buscar_historico.php?imei=' + encodeURIComponent(imei))
            .then(response => response.json())
            .then(data => {
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3">Nenhum histórico encontrado.</td></tr>';
                    return;
                }
                data.forEach(registro => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${registro.cliente}</td>
                        <td>${formatarDataBR(registro.data_saida)}</td>
                        <td>${formatarDataBR(registro.data_volta)}</td>

                    `;
                    tbody.appendChild(tr);
                });
                new bootstrap.Modal(document.getElementById('modalHistorico')).show();
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="3">Erro ao carregar histórico.</td></tr>';
                new bootstrap.Modal(document.getElementById('modalHistorico')).show();
            });
            document.querySelector('form[action="registrar_volta.php"]').addEventListener('submit', function (e) {
        const dataVolta = document.getElementById('data_volta').value;
        const imei = document.getElementById('imei_volta').value;

        // Faz uma requisição rápida para pegar a data de saída via AJAX
        fetch(`buscar_saida.php?imei=${encodeURIComponent(imei)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.data_saida && dataVolta < data.data_saida) {
                    e.preventDefault();
                    alert('A data de volta não pode ser anterior à data de saída!');
                }
            })
            .catch(() => {
                // Em caso de erro, deixa o back-end cuidar
            });
    });

    }
    </script>

    </body>
    </html>
