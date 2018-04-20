<?php
/**
 * Obtiene todas las metas de la base de datos
 */

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Paciente.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
    $idPaciente = $body['idPaciente'];
    $response = Paciente::getPacienteById($idPaciente);
    
    if ($response == false) {
        header('Content-Type: application/json');
            $errorResponse = array(
                'error' => true,
                'mensaje' => 'no se encontro',
                'paciente' => null);
        print json_encode($errorResponse);
    } else {
        header('Content-Type: application/json');
            $successResponse = array(
                'error' => false,
                'mensaje' => 'se encontro',
                'paciente' => $response);
        print json_encode($successResponse);
    }
}
?>