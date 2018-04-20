<?php
/**
 * Registra un nuevo paciente
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Cuestionario.php";
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);
    $idPaciente = $body['idPaciente'];
    $response = Cuestionario::insert($idPaciente);
    echo $response;
    if ($response['error'] == false) {
        print json_encode($response);

    } else {
        print json_encode(
            array(
                'error' => true,
                'mensaje' => 'Creación fallida')
            );
       }
    
}
?>