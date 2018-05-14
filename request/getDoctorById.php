<?php
/**
 * Obtiene todas las metas de la base de datos
 */

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Doctor.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
    $idDoctor = $body['idDoctor'];
    $response = Doctor::getDoctorById($idDoctor);
    
    if ($response == false) {
        header('Content-Type: application/json');
            $errorResponse = array(
                'error' => true,
                'mensaje' => 'no se encontro',
                'Doctor' => null);
        print json_encode($errorResponse);
    } else {
        header('Content-Type: application/json');
            $successResponse = array(
                'error' => false,
                'mensaje' => 'se encontro',
                'doctor' => $response['doctor']);
        print json_encode($successResponse);
    }
}
?>

