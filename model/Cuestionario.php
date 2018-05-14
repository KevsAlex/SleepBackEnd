<?php

/**
 * Representa el la estructura de las metas
 * almacenadas en la base de datos
 */
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/dreamBack/DatabaseSingleton.php";



class Cuestionario{
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
    public static function insert($idPaciente) {
        // Sentencia INSERT
        $query = "INSERT INTO Cuestionario (idCuestionario,idPaciente)
        VALUES (NULL,?);";
        // Preparar la sentencia
        try{
            $conection = Database::getInstance()->getDb();
            $sentencia = $conection->prepare($query);
            $exito = $sentencia->execute(array($idPaciente));
            $last_id = $conection->lastInsertId();
            
            $cuestionarioInsertado = Cuestionario::getCuestionarioById($last_id);
            
            $response = array(
                'error' => false,
                'mensaje' => 'registro exitoso',
                'cuestionario' => $cuestionarioInsertado['cuestionario']);
            return $response;

        }
        catch (PDOException $e){
             print $e->getMessage ();
        }
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

    public static function getCuestionarioById($idCuestionario){
        // Consulta de la meta
        $query = "SELECT    idCuestionario,
                            idPaciente
                            FROM Cuestionario
                            WHERE  idCuestionario = ?;";

        try {
            
            $comando = Database::getInstance()->getDb()->prepare($query);
            $comando->execute(array($idCuestionario));
            $cuestionario = $comando->fetch(PDO::FETCH_ASSOC);
               $response = array(
                'error' => false,
                'mensaje' => 'get cuestionario exitoso',
                'cuestionario' => $cuestionario);
            return $response;

        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

     public static function getCuestionariosFromPaciente($idPaciente){
        // Consulta de la meta
        $query = "SELECT    *
                            FROM Cuestionario c
                            WHERE c.idPaciente = ?;";

        try {
            
            $comando = Database::getInstance()->getDb()->prepare($query);
            $comando->execute(array($idPaciente));
            $cuestionarios = $comando->fetchAll();
            if (!$cuestionarios){
              $response = array(
                'error' => true,
                'mensaje' => 'no hay ningun cuestionario asociado',
                'cuestionarios' => NULL);
                return $response;
            }
               $response = array(
                'error' => false,
                'mensaje' => 'hay cuestionarios yeaaaahhh!!',
                'cuestionarios' => $cuestionarios);
                return $response;

        } catch (PDOException $e) {
            print $e->getMessage ();
        }
    }

   
}

?>