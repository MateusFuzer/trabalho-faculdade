<?php
require("..//../../../data/connect_data.php");

// Verifica se existe uma requisição para deletar um paciente
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Preparar a consulta de exclusão
    $sql_delete = "DELETE FROM pacientes WHERE id_paciente = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $delete_id);
    $stmt_delete->execute();

    $message = '';
    $status = '';
    if ($stmt_delete->affected_rows > 0) {
        $message = 'Paciente deletado com sucesso!';
        $status = 'success';
    } else {
        $message = 'Erro ao deletar o paciente.';
        $status = 'error';
    }

    $stmt_delete->close();

    // Passando a mensagem e o status para o JavaScript
    echo "<script>showToast('$message', '$status');</script>";
}

// Consulta para buscar os pacientes
$sql = "SELECT nome, idade, cidade, leito, id_paciente FROM pacientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">
    <title>Lista de Pacientes</title>
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
                    <img src="../../../assets/img/lista-de-controle.png" alt="icone lista de pacientes" class="icone_aside">
                Paciente
                </div>
            </aside>
            <main class="main_cont">
                <div class="box_cont">
                    <div>
                        <a href="../cadastro/cadastro.php">
                            <button>Cadastrar Paciente</button>
                        </a>
                        
                        <h2>Lista de Pacientes</h2>
                    </div>
                    <table class="pacientes_table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Idade</th>
                                <th>Cidade</th>
                                <th>Leito</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                // Loop pelos resultados da consulta
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['idade']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['cidade']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['leito']) . "</td>";
                                    echo "<td>
                                            <a href='../editar/editar.php?id=" . urlencode($row['id_paciente']) . "' ><button class='btn editar'>Editar</button></a>
                                            <form method='POST' action='' style='display:inline;'>
                                                <input type='hidden' name='delete_id' value='" . $row['id_paciente'] . "'>
                                                <button type='submit' class='btn deletar'>Deletar</button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Nenhum paciente encontrado.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>            
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast" class="toast"></div>

    <script>
        function showToast(message, status) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = 'toast show ' + status;

            // Remove a classe 'show' após 3 segundos para ocultar o toast
            setTimeout(function () {
                toast.className = toast.className.replace('show', '');
            }, 3000);
        }
    </script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
