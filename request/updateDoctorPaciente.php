<?php
/**
 * Registra un nuevo paciente
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/dreamBack/model/Doctor.php";
include_once "$root/dreamBack/model/Paciente.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);
    $idPaciente = $body['idPaciente'];
    $claveDoctor = $body['claveDoctor'];
    //ver si la clave con ese doctor existe
    $doctor = Doctor::getDoctorByClave($claveDoctor);
    if (!$doctor["error"]){
        //Si exixste setearle el idPaciente
        $objetoDoctor = $doctor["doctor"];
        $idDoctor = $objetoDoctor["idDoctor"];
        $reaspuesta = Paciente::update($idDoctor,$idPaciente);
        //reaspuesta UPDATE Paciente SET idDoctor=?WHERE idPaciente=?
        print json_encode(
            array(
                'error' => false,
                'mensaje' => 'se ha ejecutado el cambio correctamente',
                'doctor' => $objetoDoctor)
            );

    }else{
        print json_encode(
            array(
                'error' => true,
                'mensaje' => 'no se encontro al doctor con esa clave',
                'doctor' => "")
            );
    }
    
    
}
?>