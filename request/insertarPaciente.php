<?php
/**
 * Registra un nuevo paciente
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/model/Paciente.php";
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);
    $nombre = $body['nombre'];
    $apellidoPaterno = $body['apellidoPaterno'];
    $apellidoMaterno = $body['apellidoMaterno'];
    $direccion = $body['direccion'];
    $telefono = $body['telefono'];
    $sexo = $body['sexo'];
    $password = $body['password'];
    $correo =  $body['correo'];


    
    // Obtenemos todos los usuarios y verificamos si ya existe
    $response = Paciente::insert( $nombre,
                                  $apellidoPaterno, 
                                  $apellidoMaterno,
                                  $direccion,
                                  $telefono,
                                  $sexo,
                                  $password,
                                  $correo);
   
    
    
    
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