<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/DatabaseSingleton.php";



class Pregunta{
    //Constructor de la clase 
    function __construct()
    {
    }

    /**
     * Retorna en la fila especificada de la tabla 'UsuariosFirebase'
     *
     * @return array Datos del registro
     */
    public static function getAll()
    {
        $consulta = "SELECT * FROM DailyCashOuts";
        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute();

            return $comando->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    
    /**
     * Insertar una nuevo token
     *
     * @param $token el token a insertar en la base de datos
     * @return PDOStatement
     */
    public static function insert($pregunta,
                                  $respuesta,
                                  $tipoPregunta,
                                  $idCuestionario,
                                  $score) {
        // Sentencia INSERT
        $query = "INSERT INTO Pregunta (pregunta,
                                        respuesta,
                                        tipoPregunta,
                                        idCuestionario,
                                        score)
        VALUES (?,?,?,?,?);";
        // Preparar la sentencia
        try{
            $conection = Database::getInstance()->getDb();
            $sentencia = $conection->prepare($query);
            $exito = $sentencia->execute(array( $pregunta,
                                                $respuesta,
                                                $tipoPregunta,
                                                $idCuestionario,
                                                $score));
            $last_id = $conection->lastInsertId();
            $preguntaInsertada = Pregunta::getpreguntaById($last_id);
            $preguntaInsertada['score'] =  floatval($preguntaInsertada['score']);
            $response = array(
                'error' => false,
                'mensaje' => 'registro exitoso',
                'pregunta' => $preguntaInsertada);
            
            return $response;

        }
        catch (PDOException $e){
             print $e->getMessage ();
        }
        
        //Regresa el token que se acaba de insertar en la base de datos
       }

       /**
       * Actualiza el doctor del paciente
       * 
       *
       * @param 
       * @return mixed
       */
       public static function update( $claveDoctor,$idPaciente){
        // Creando consulta UPDATE

        $query = "UPDATE Paciente" .
            " SET idDoctor=?" .
            "WHERE idPaciente=?";

        // Preparar la sentencia
        $cmd = Database::getInstance()->getDb()->prepare($query);
        // Relacionar y ejecutar la sentencia
        $cmd->execute(array($claveDoctor, $idPaciente));
        return $cmd;
    }

    public static function getpreguntaById($idPregunta){
        // Consulta de la meta
        $consulta = "SELECT *
                            FROM Pregunta
                            WHERE idPregunta = ?";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($idPregunta));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

   
}

?>