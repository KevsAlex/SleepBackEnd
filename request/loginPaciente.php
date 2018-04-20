<?php
/**
 * Obtiene todas las metas de la base de datos
 */

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Paciente.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
    $correo = $body['correo'];
    $password = $body['password'];
    $response = Paciente::getPacienteByCorreo($correo,$password);
    if (!$response['error']) {
        header('Content-Type: application/json');
        print json_encode($response);
    } else {
        header('Content-Type: application/json');
        print json_encode($response);
    }
}
?>