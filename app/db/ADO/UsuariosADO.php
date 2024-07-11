<?php

require_once "AccesoDatos.php"; 

class UsuariosADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new UsuariosADO();
        }
        return self::$objAccesoDatos;
    }

    //SELECT
    public function traerTodosLosUsuarios(){
        //consulta
        $sql = "SELECT * FROM `usuarios`";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function ObtenerPrecioPorNombre($nombre, $stock){
        $stmt = $this->prepararConsulta("SELECT precio FROM `tienda` WHERE nombre = ?");
        $stmt->bindParam(1, $nombre);

        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ingreso = $result[0]["precio"] * $stock;
            return $ingreso;
        } catch (PDOException $e) {
            return false;
        }
    }

    //INSERT
    public function altaUsuario($user)
    {   
        $sql = "INSERT INTO `usuarios` (`mail`, `usuario`, `clave`, `perfil`, `foto`, `fecha_de_alta`) 
        VALUES (:mail, :usuario, :clave, :perfil, :foto, :fecha_de_alta)";
        
        $stmt = $this->prepararConsulta($sql);

        // Vincular los valores a los parámetros
        $stmt->bindParam(':mail', $user->_mail);
        $stmt->bindParam(':usuario', $user->_usuario);
        $stmt->bindParam(':clave', $user->_contraseña);
        $stmt->bindParam(':perfil', $user->_perfil);
        $stmt->bindParam(':foto', $user->_path);
        $stmt->bindParam(':fecha_de_alta', $user->_fechaAlta);

        try {
            // Ejecutar la consulta
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

}