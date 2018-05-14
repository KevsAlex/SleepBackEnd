<?php
/**
 * Obtiene todas las metas de la base de datos
 */

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/dreamBack/model/Paciente.php";
include_once "$root/dreamBack/model/Cuestionario.php";
include_once "$root/dreamBack/model/Pregunta.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
    $idDoctor = $body['idDoctor'];
    $response = Paciente::getPacientesWith($idDoctor);
    
    if ($response['error'] == false) {
        $errorResponse = array(
                'error' => true,
                'mensaje' => 'no se encontro',
                'paciente' => null);
        print json_encode($errorResponse);
    } else {
        
            $successResponse = array(
                'error' => false,
                'mensaje' => 'se encontro',
                'pacientes' => $response['pacientes']);
            //print json_encode($successResponse);
            $pacientes = $response['pacientes'];
            
            $idCuestionarios = array(
                'cuestionarios' => "");

            for ($i = 0; $i < count($pacientes); $i++) {
                $paciente = $pacientes[$i];
                $id =  $paciente['idPaciente'];
                $arrayidCuestionarios = Cuestionario::getCuestionariosFromPaciente($id);
                for ($j = 0; $j < count($arrayidCuestionarios['cuestionarios']); $j++){
                    $elementoCuestionario = $arrayidCuestionarios['cuestionarios'][$j];
                    $idCuestionarios['cuestionarios'][] = $elementoCuestionario;

                }
                
                //$idCuestionarios['cuestionarios'][] = $arrayidCuestionarios['cuestionarios'];
            }

            $preguntasResponse = array(
                'error' => false,
                'preguntas' => ""

                );

            for ($i = 0; $i < count($idCuestionarios['cuestionarios']); $i++){
                $cuestionario = $idCuestionarios['cuestionarios'][$i];
                $idCuestionario = $cuestionario['idCuestionario'];
                $preguntas = Pregunta::getpreguntaByCuestionario($idCuestionario);
                
                if ($preguntas['error']){
                    continue;
                }
                $preguntasResponse['preguntas'][] = $preguntas['preguntas'];
                
            }
            //Buscar el pinchi cuestionario relacionado
            for ($m = 0; $m < count($preguntasResponse['preguntas']); $m++){
                $alereadyFound = false;
                $elemento = $preguntasResponse['preguntas'][$m];
                for ($e = 0; $e < count($elemento); $e++){    
                    $obj = $elemento[$e];
                    for($j = 0; $j < count($idCuestionarios['cuestionarios']); $j++){
                        $cuestionarioRelacionado = $idCuestionarios['cuestionarios'][$j];
                        if ($cuestionarioRelacionado['idCuestionario'] == $obj['idCuestionario']){
                            
                            for ($a = 0; $a < count($pacientes); $a++){
                                $p = $pacientes[$a];
                                if ($cuestionarioRelacionado['idPaciente'] == $p['idPaciente']){
                                    
                                    if ($alereadyFound == false){
                                        $preguntasResponse['preguntas'][$m][]['paciente'] = $p['nombre'] ;
                                        //$preguntasResponse['preguntas'][$m][0][]['paciente'] = $p['nombre'] ;
                                        $alereadyFound = true;
                                    }
                                }

                            }

                        }
                    }
                } 
            }
            
            print json_encode($preguntasResponse);   
            
    }
}
?>