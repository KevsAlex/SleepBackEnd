<?php
/**
 * Obtiene todas las metas de la base de datos
 */

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Doctor.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
    $nombre = $body['nombre'];
    $claveDoctor = $body['claveDoctor'];
    $response = Doctor::loginDoctor($nombre,$claveDoctor);
    if (!$response['error']) {
        header('Content-Type: application/json');
        print json_encode($response);
    } else {
        print json_encode(array(
            "error" => true,
            "mensaje" => "Ha ocurrido un error"
        ));
    }
}
?>