<?php
require("..//../../../data/connect_data.php");

// Verificar se o ID do paciente foi passado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para buscar os dados do paciente
    $sql_edit = "SELECT * FROM pacientes WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();

    if ($result_edit->num_rows > 0) {
        $patient = $result_edit->fetch_assoc();
    } else {
        echo "Paciente não encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do paciente não foi fornecido.";
    exit;
}


require("..//../../../data/connect_data.php");

// Verificar se o ID do paciente foi passado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para buscar os dados do paciente
    $sql_edit = "SELECT * FROM pacientes WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql_edit);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();

    if ($result_edit->num_rows > 0) {
        $patient = $result_edit->fetch_assoc();
    } else {
        echo "Paciente não encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do paciente não foi fornecido.";
    exit;
}

// Processar a atualização dos dados do paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $cidade = $_POST['cidade'];
    $leito = $_POST['leito'];

    // Comparar os dados enviados com os dados originais
    if ($nome == $patient['nome'] && $idade == $patient['idade'] && $cidade == $patient['cidade'] && $leito == $patient['leito']) {
        // Se os dados não foram alterados, não fazer nada
        $response = [
            'status' => 'no_change',
            'message' => 'Nenhuma alteração detectada.'
        ];
    } else {
        // Atualizar paciente no banco de dados
        $sql_update = "UPDATE pacientes SET nome = ?, idade = ?, cidade = ?, leito = ? WHERE id_paciente = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sisis", $nome, $idade, $cidade, $leito, $id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows > 0) {
            $response = [
                'status' => 'success',
                'message' => 'Paciente atualizado com sucesso!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Erro ao atualizar o paciente.'
            ];
        }

        $stmt_update->close();
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
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="./editar.css">
   
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
                    <h2>Editar Paciente</h2>

                    <?php if (isset($patient)): ?>
                        <form id="editPatientForm">
                            <input type="hidden" name="id" value="<?php echo $patient['id_paciente']; ?>">

                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($patient['nome']); ?>" required>

                            <label for="idade">Idade:</label>
                            <input type="number" name="idade" id="idade" value="<?php echo htmlspecialchars($patient['idade']); ?>" required>

                            <label for="cidade">Cidade:</label>
                            <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($patient['cidade']); ?>" required>

                            <label for="leito">Leito:</label>
                            <input type="text" name="leito" id="leito" value="<?php echo htmlspecialchars($patient['leito']); ?>" required>

                            <button type="submit" class="btn atualizar">Atualizar</button>
                        </form>
                    <?php else: ?>
                        <p>Paciente não encontrado.</p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Toast de notificação -->
    <div id="toast" class="toast"></div>

    <script>
        // Função AJAX para atualizar os dados sem refresh
        document.getElementById('editPatientForm').addEventListener('submit', function(e) {
            e.preventDefault();  // Impede o envio normal do formulário

            var formData = new FormData(this); // Captura os dados do formulário

            // Enviar dados via AJAX
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
                } else {
                    toast.className = 'toast error show';
                    toast.textContent = data.message;
                }
                setTimeout(function() {
                    toast.classList.remove('show');
                }, 3000); // Esconde o toast após 3 segundos
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
