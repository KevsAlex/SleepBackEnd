<?php
/**
 * Registra un nuevo paciente
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/dreamBack/model/Pregunta.php";
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);
    $pregunta = $body['pregunta'];
    $respuesta = $body['respuesta'];
    $tipoPregunta = $body['tipoPregunta'];
    $idCuestionario = $body['idCuestionario'];
    $score = $body['score'];
    
    // Obtenemos todos los usuarios y verificamos si ya existe
    $response = Pregunta::insert( $pregunta,
                                  $respuesta, 
                                  $tipoPregunta,
                                  $idCuestionario,
                                  $score);
    
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