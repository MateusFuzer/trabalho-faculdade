<?php
require("..//../../../data/connect_data.php");

// Processar o cadastro dos dados do paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $cidade = $_POST['cidade'];
    $leito = $_POST['leito'];

    // Validar se os campos estão vazios
    if (empty($nome) || empty($idade) || empty($cidade) || empty($leito)) {
        $response = [
            'status' => 'error',
            'message' => 'Por favor, preencha todos os campos.'
        ];
    } else {
        // Inserir os dados do paciente no banco de dados
        $sql_insert = "INSERT INTO pacientes (nome, idade, cidade, leito) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("siss", $nome, $idade, $cidade, $leito);
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            $response = [
                'status' => 'success',
                'message' => 'Paciente cadastrado com sucesso!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao cadastrar o paciente.'
            ];
        }

        $stmt_insert->close();
    }

    // Enviar resposta JSON
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Paciente</title>
    <link rel="stylesheet" href="./cadastro.css">
</head>

<body>
    <div class="container">
        <header class="header_container">
            <img src="../../../assets/img/hospital.png" alt="Logo hospital" class="logo">
            <span>Hospital</span>
        </header>

        <div class="info_cont">
            <aside class="aside_cont">
                <div class="item_aside">
                    <img src="../../../assets/img/lista-de-controle.png" alt="Lista de pacientes" class="icone_aside">
                    <a href="../Lista/index.php">Pacientes</a>
                </div>
            </aside>

            <main class="main_cont">
                <div class="box_cont">
                    <h2>Cadastrar Paciente</h2>

                    <!-- Formulário de Cadastro -->
                    <form id="cadastroForm" method="POST">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" required>

                        <label for="idade">Idade:</label>
                        <input type="number" name="idade" id="idade" required>

                        <label for="cidade">Cidade:</label>
                        <input type="text" name="cidade" id="cidade" required>

                        <label for="leito">Leito:</label>
                        <input type="text" name="leito" id="leito" required>

                        <button type="submit" class="btn cadastrar">Cadastrar</button>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <!-- Toast de notificação -->
    <div id="toast" class="toast"></div>

    <script>
        // Enviar o formulário via AJAX
        document.getElementById('cadastroForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);

            fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var toast = document.getElementById('toast');
                    if (data.status === 'success') {
                        toast.className = 'toast success show';
                        toast.textContent = data.message;

                    } else if (data.status === 'error') {
                        toast.className = 'toast error show';
                        toast.textContent = data.message;
                    }

                    setTimeout(function() {
                        toast.classList.remove('show');
                        window.location.href = 'http://localhost/trabalho-faculdade/src/app/modules/Lista/index.php'
                    }, 3000);
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
        });
    </script>
</body>

</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>