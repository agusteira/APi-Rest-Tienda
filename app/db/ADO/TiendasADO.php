<?php

require_once "AccesoDatos.php"; 

class TiendasADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new TiendasADO();
        }
        return self::$objAccesoDatos;
    }

    //SELECT
    public function traerTodosLosTiendas(){
        //consulta
        $sql = "SELECT * FROM `Tiendas`";
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

    public function ComprobarExistencia($tienda)
    {   
        $sql = "SELECT * FROM `tienda` WHERE nombre = ? AND tipo = ?";
        
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(1, $tienda->_nombre);
        $stmt->bindParam(2,  $tienda->_tipo);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            //ejecuta la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result != null){
                return true;
            }
            else{
                return false;
            }
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ComprobarRegistro($nombre,$tipo,$color)
    {   
        $sql = "SELECT * FROM `tienda` WHERE nombre = ? AND tipo = ? AND color = ?";
        
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2,  $tipo);
        $stmt->bindParam(3,  $color);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result != null){
                return true;
            }
            else{
                return false;
            }
            
        } catch (PDOException $e) {
            return false;
        }
    }

    public function ComprobarStock($nombre, $tipo, $talla, $stock)
    {   
        $sql = "SELECT stock FROM `tienda` WHERE nombre = ? AND tipo = ? AND talla = ?";
        
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2,  $tipo);
        $stmt->bindParam(3,  $talla);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($result != null && $result[0]["stock"] >= $stock){
                return true;
            }
            else{
                return false;
            }
            
        } catch (PDOException $e) {
            return false;
        }
    }
    public function ListarProductosEntreValores($valor1, $valor2){
        $sql = "SELECT * FROM tienda WHERE precio BETWEEN ? AND ?";
        $stmt = $this->prepararConsulta($sql);
        $stmt->bindParam(1, $valor1);
        $stmt->bindParam(2, $valor2);
        //prepara la consulta
        
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    //INSERT
    public function altaTienda($tienda)
    {   
        $sql = "INSERT INTO `tienda` (`nombre`, `precio`, `tipo`, `talla`, `color`, `stock`, `foto`) 
            VALUES (:nombre, :precio, :tipo, :talla, :color, :stock, :foto)";
        
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(':nombre', $tienda->_nombre);
        $stmt->bindParam(':precio', $tienda->_precio);
        $stmt->bindParam(':tipo',  $tienda->_tipo);
        $stmt->bindParam(':talla', $tienda->_talla);
        $stmt->bindParam(':color', $tienda->_color);
        $stmt->bindParam(':stock',  $tienda->_stock);
        $stmt->bindParam(':foto',  $tienda->_path);
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //UPDATE
    public function ActualizarTienda($tienda)
    {   
        $sql = "UPDATE `tienda` SET `precio` = :precio, `stock` = `stock` + :stock WHERE `nombre` = :nombre AND `tipo` = :tipo";

        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(':nombre', $tienda->_nombre);
        $stmt->bindParam(':precio', $tienda->_precio);
        $stmt->bindParam(':tipo',  $tienda->_tipo);
        $stmt->bindParam(':stock',  $tienda->_stock);
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

    public function DescontarStock($nombre, $tipo, $talla, $stock)
    {   
        $sql = "UPDATE `tienda` SET `stock` = `stock` - :cantidad WHERE `nombre` = :nombre AND `tipo` = :tipo AND `talla` = :talla";
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(':cantidad',  $stock);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':tipo',  $tipo);
        $stmt->bindParam(':talla',$talla);
        
        
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }
}