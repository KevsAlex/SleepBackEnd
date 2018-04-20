<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include "$root/dreamBack/DatabaseSingleton.php";



class Paciente{
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
                                  $sexo,
                                  $password,
                                  $correo) {
        // Sentencia INSERT
        $query = "INSERT INTO Paciente (idPaciente,
                                        nombre ,
                                        apellidoPaterno,
                                        apellidoMaterno,
                                        direccion,
                                        telefono,
                                        sexo,
                                        idDoctor,
                                        password,
                                        correo)
        VALUES (NULL,?,?,?,?,?,?,?,?,?);";
        // Preparar la sentencia
        try{
            $conection = Database::getInstance()->getDb();
            $sentencia = $conection->prepare($query);
            $exito = $sentencia->execute(array( $nombre,
                                                $aplellidoPaterno,
                                                $apellidoMaterno,
                                                $direccion,
                                                $telefono,
                                                $sexo,
                                                $idDoctor,
                                                $password,
                                                $correo));
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

    public static function getPacienteById($idPaciente){
        // Consulta de la meta
        $consulta = "SELECT idPaciente,
                            nombre,
                            idDoctor,
                            idPaciente,
                            idC
                            FROM Paciente
                            WHERE idPaciente = ?";

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($idPaciente));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);
            return $row;

        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

    public static function getPacienteByCorreo($correo,$password){
        // Consulta de la meta
        $consulta = "SELECT idPaciente,
                            nombre,
                            idDoctor
                            FROM Paciente
                            WHERE correo = ? AND password = ?" ;

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($correo,$password));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);

            $response = array(
                'error' => $row ? true : false,
                'mensaje' => $row ? 'registro exitoso' : 'registro no exitoso',
                'paciente' => $row);
            
            return $response;
        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

   public static function getCuestionarioById($idPaciente) {
    // Consulta de la meta
        $consulta = "SELECT idPaciente,
                            nombre,
                            idDoctor
                            FROM Paciente
                            WHERE correo = ? AND password = ?" ;

        try {
            // Preparar sentencia
            $comando = Database::getInstance()->getDb()->prepare($consulta);
            // Ejecutar sentencia preparada
            $comando->execute(array($correo,$password));
            // Capturar primera fila del resultado
            $row = $comando->fetch(PDO::FETCH_ASSOC);

            $response = array(
                'error' => $row ? true : false,
                'mensaje' => $row ? 'registro exitoso' : 'registro no exitoso',
                'paciente' => $row);
            
            return $response;
        } catch (PDOException $e) {
            print $e->getMessage ();
        }
        
    }







   
}

?>