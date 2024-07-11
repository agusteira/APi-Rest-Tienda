<?php

require_once "AccesoDatos.php"; 

class VentasADO extends AccesoDatos
{
    protected static $objAccesoDatos; //Cada hija de acceso datos DEBE tener su propio ObjADO porque si no se pueden mezclas y provocar errores
    private function __construct()
    {
        parent::__construct();
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new VentasADO();
        }
        return self::$objAccesoDatos;
    }

    //SELECT
    public function traerTodosLosVentas(){
        //consulta
        $sql = "SELECT * FROM `Ventas`";
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
    public function VerificarVentaPorID($id){
        $stmt = $this->prepararConsulta("SELECT * FROM `ventas` WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

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
    public function TraerProductosVendidos($fecha){
        $sql = "SELECT COUNT(*) as cantidad FROM ventas WHERE DATE(fechaPedido) = ?";
        $stmt = $this->prepararConsulta($sql);
        if ($fecha == null){
            $fecha = date("Y-m-d", strtotime("-1 day")); 
        }
        $stmt->bindParam(1, $fecha);
        //prepara la consulta
        
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

    public function TraerProductoMasVendido(){
        $sql = "SELECT nombre, COUNT(*) as cantidad FROM ventas GROUP BY nombre ORDER BY cantidad DESC LIMIT 1 ";
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

    public function TraerVentasPorUsuario($email){
        $sql = "SELECT * FROM ventas WHERE email = :email ";
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(":email", $email);

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

    public function TraerVentasPorProducto($nombre){
        $sql = "SELECT * FROM ventas WHERE nombre = :nombre ";
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(":nombre", $nombre);

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

    public function TraerNombres($fecha){
        if ($fecha == null){
            $sql = "SELECT nombre, stock FROM ventas";
            $stmt = $this->prepararConsulta($sql);
        }else{
            $sql = "SELECT nombre, stock FROM ventas WHERE DATE(fechaPedido) = ?";
            $stmt = $this->prepararConsulta($sql);
            $stmt->bindParam(1, $fecha);
        }
        
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

    //INSERT
    public function altaVenta($venta)
    {   
        $sql = "INSERT INTO `ventas` (`email`, `nombre`, `tipo`, `talla`, `stock`, `fechaPedido`, `foto`) 
        VALUES (:email, :nombre, :tipo, :talla, :stock, :fechaPedido, :foto)";
    
        $stmt = $this->prepararConsulta($sql);
        // Vincular los valores a los parámetros
        $stmt->bindParam(':email', $venta->_email);
        $stmt->bindParam(':nombre', $venta->_nombre);
        $stmt->bindParam(':tipo',  $venta->_tipo);
        $stmt->bindParam(':talla', $venta->_talla);
        $stmt->bindParam(':fechaPedido', $venta->_datetime);
        $stmt->bindParam(':stock',  $venta->_stock);
        $stmt->bindParam(':foto',  $venta->_path);
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    //UPDATE

    public function ModificarVenta($email,$nombre,$tipo,$talla,$stock, $id){
        //consulta
        $sql = "UPDATE ventas SET email = :email, nombre = :nombre, tipo = :tipo, talla = :talla, stock = :stock WHERE id = :id";
        //prepara la consulta
        $stmt = $this->prepararConsulta($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':talla', $talla);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        try {
            //ejecuta la consulta
            $stmt->execute();
            //obtiene los datos de la consulta
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
