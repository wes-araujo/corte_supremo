<?php
header('Content-Type: application/json');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurante_reservas"; 


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$email = sanitize_input($_POST['email'] ?? '');
$data_reserva = sanitize_input($_POST['data'] ?? ''); 
$hora = sanitize_input($_POST['hora'] ?? '');
$grupo = sanitize_input($_POST['grupo'] ?? '');
$unidade = sanitize_input($_POST['unidade'] ?? '');


$itensMenu = json_decode($_POST['itens_menu'] ?? '[]', true);
$quantidadesMenu = json_decode($_POST['quantidades_menu'] ?? '[]', true);
$precosMenu = json_decode($_POST['precos_menu'] ?? '[]', true); 


if (empty($email) || empty($data_reserva) || empty($hora) || empty($grupo) || empty($unidade)) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios da reserva.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Formato de e-mail inválido.']);
    exit;
}


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao conectar ao banco de dados: ' . $conn->connect_error]);
    exit;
}

$conn->begin_transaction(); 

try {
    
    $sql = "INSERT INTO reservas (email, data, hora, grupo, unidade) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
   
    $stmt->bind_param("sssss", $email, $data_reserva, $hora, $grupo, $unidade);

    if (!$stmt->execute()) {
        throw new Exception('Erro ao inserir reserva: ' . $stmt->error);
    }
    $reserva_id = $conn->insert_id;

    
    $total = 0;
    $itensFormatados = "";
    if (!empty($itensMenu) && count($itensMenu) === count($quantidadesMenu) && count($itensMenu) === count($precosMenu)) {
        $sql_menu = "INSERT INTO reserva_menu (reserva_id, item, quantidade) VALUES (?, ?, ?)";
        $stmt_menu = $conn->prepare($sql_menu);
       
        $stmt_menu->bind_param("isi", $reserva_id, $item_nome, $item_quantidade);

        for ($i = 0; $i < count($itensMenu); $i++) {
            $item_nome = sanitize_input($itensMenu[$i]);
            $item_quantidade = intval($quantidadesMenu[$i]);
            
            $item_preco = isset($precosMenu[$i]) ? floatval($precosMenu[$i]) : 0.00;
            
            if ($item_quantidade <= 0) continue;

            $subtotal = $item_quantidade * $item_preco;
            $total += $subtotal;

            $itensFormatados .= htmlspecialchars($item_nome) . " - " . $item_quantidade . " x R$ " . number_format($item_preco, 2, ',', '.') . " = R$ " . number_format($subtotal, 2, ',', '.') . "<br>";

            if (!$stmt_menu->execute()) {
                throw new Exception('Erro ao inserir item do menu: ' . $stmt_menu->error);
            }
        }
        $stmt_menu->close();
    }

    
    $mail = new PHPMailer(true);
    try {
    
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';   
        $mail->SMTPAuth = true;
        $mail->Username = 'araujo.wesley1@gmail.com';   
        $mail->Password = 'vyzfqwfxgsckokbs'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587;     
        $mail->CharSet = 'UTF-8'; 

        $mail->setFrom('araujo.wesley1@gmail.com', 'Corte Supremo'); 
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Reserva - Corte Supremo';

        $mail->Body = "
            <h2>Confirmação de Reserva</h2>
            <p>Olá,</p>
            <p>Sua reserva foi confirmada para:</p>
            <ul>
                <li><strong>Data:</strong> " . htmlspecialchars($data_reserva) . "</li>
                <li><strong>Hora:</strong> " . htmlspecialchars($hora) . "</li>
                <li><strong>Grupo:</strong> " . htmlspecialchars($grupo) . "</li>
                <li><strong>Unidade:</strong> " . htmlspecialchars($unidade) . "</li>
            </ul>";
        
        if (!empty($itensFormatados)) {
            $mail->Body .= "<h3>Itens do Menu Pré-selecionados:</h3>
                           <p>" . $itensFormatados . "</p>
                           <h3>Total dos Itens: R$ " . number_format($total, 2, ',', '.') . "</h3>";
        } else {
            $mail->Body .= "<p>Nenhum item do menu foi pré-selecionado.</p>";
        }
        
        $mail->Body .= "<p>Obrigado por reservar conosco!</p>";

        $mail->send();
        error_log("Email enviado com sucesso para: " . $email);

        $conn->commit(); 
        echo json_encode(['status' => 'success', 'message' => 'Reserva realizada com sucesso! Verifique seu e-mail.']);

    } catch (Exception $e) {
        $conn->rollback(); 
        error_log("PHPMailer Error for " . $email . ": " . $mail->ErrorInfo . " | General Exception: " . $e->getMessage());
       
        echo json_encode(['status' => 'error', 'message' => 'Reserva salva, mas houve um problema ao enviar o e-mail de confirmação. Por favor, contate o suporte. Erro: ' . $mail->ErrorInfo]);
    }

} catch (Exception $e) {
    $conn->rollback(); 
    error_log("Database/General Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Erro geral no servidor: ' . $e->getMessage()]);
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>