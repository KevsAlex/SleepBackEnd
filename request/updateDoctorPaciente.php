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
    $idPaciente = $body['idPaciente'];
    $claveDoctor = $body['claveDoctor'];
    // Obtenemos todos los usuarios y verificamos si ya existe
    $isPacienteInserted = Paciente::update( $claveDoctor,$idPaciente);

    
    if ($isPacienteInserted) {
        $pacienteActualizado = Paciente::getPacienteById($idPaciente);
        print json_encode(
            array(
                'error' => true,
                'mensaje' => 'actualizacion exitosa',
                'paciente' => $pacienteActualizado)
            );

    } else {
        print json_encode(
            array(
                'error' => false,
                'mensaje' => 'Creación fallida')
            );
       }
    
}
?>