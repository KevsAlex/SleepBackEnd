<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/DatabaseSingleton.php";



class Doctor{
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
    public static function insert($nombre,
                                  $aplellidoPaterno,
                                  $apellidoMaterno,
                                  $direccion,
                                  $telefono,
                                  $sexo) {
        // Sentencia INSERT
        $query = "INSERT INTO Paciente (idPaciente,
                                        nombre ,
                                        apellidoPaterno,
                                        apellidoMaterno,
                                        direccion,
                                        telefono,
                                        sexo,
                                        idDoctor)
        VALUES (NULL,?,?,?,?,?,?,NULL);";
        // Preparar la sentencia
        try{
            $conection = Database::getInstance()->getDb();
            $sentencia = $conection->prepare($query);
            $exito = $sentencia->execute(array( $nombre,
                                                $aplellidoPaterno,
                                                $apellidoMaterno,
                                                $direccion,
                                                $telefono,
                                                $sexo));
            $last_id = $conection->lastInsertId();
            $pacienteInsertado = Paciente::getPacienteById($last_id);
            $response = array(
                'error' => false,
                'mensaje' => 'registro exitoso',
                'paciente' => $pacienteInsertado);
            
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

    public static function loginDoctor($nombre,$claveDoctor){
        // Consulta de la meta
        $query = "SELECT    idDoctor,
                            nombre,
                            password,
                            claveDoctor
                            FROM Doctor
                            WHERE nombre = ? AND claveDoctor = ?;";

        try {
            
            $comando = Database::getInstance()->getDb()->prepare($query);
            $comando->execute(array($nombre,$claveDoctor));
            $doctorLogeado = $comando->fetch(PDO::FETCH_ASSOC);
               $response = array(
                'error' => false,
                'mensaje' => 'loggin exitoso',
                'doctor' => $doctorLogeado);
            return $response;

        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

    public static function getDoctorByClave($claveDoctor){
        // Consulta de la meta
        $query = "SELECT    idDoctor,
                            nombre,
                            password,
                            claveDoctor
                            FROM Doctor
                            WHERE claveDoctor = ?;";

        try {
            
            $comando = Database::getInstance()->getDb()->prepare($query);
            $comando->execute(array($claveDoctor));
            $doctorEncontrado = $comando->fetch(PDO::FETCH_ASSOC);
            if ($doctorEncontrado == false){
              $response = array(
                'error' => trure,
                'mensaje' => 'no se encontro',
                'doctor' => NULL);
                return $response;
            }else{
              $response = array(
                'error' => false,
                'mensaje' => 'se encontro',
                'doctor' => $doctorEncontrado);
                return $response;
            }
      } catch (PDOException $e) {
          $response = array(
                'error' => true,
                'mensaje' => $e->getMessage(),
                'doctor' => NULL);
            return $response;
        }
    }



   
}

?>