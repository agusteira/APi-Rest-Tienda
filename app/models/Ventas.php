<?php

include_once "././db/ADO/VentasADO.php";
class Ventas{
    public $_email;
    public $_nombre;
    public $_tipo;
    public $_talla;
    public $_stock;
    public $_datetime;
    public $_path;

    public function __construct ($email,$nombre,$tipo,$talla,$stock, $datetime, $path){
        $this->_email = $email;
        $this->_nombre = $nombre;
        $this->_tipo = $tipo;
        $this->_talla = $talla;
        $this->_stock = $stock;
        $this->_datetime = $datetime;
        $this->_path = $path;
    }

    //Crear
    public static function CrearVenta($email,$nombre,$tipo,$talla,$stock, $foto){
        $datetime = date("Y-m-d H:i:s");

        $path = self::CargarFoto($foto,$nombre,$tipo,$talla,$email);
        
        $venta = new Ventas($email,$nombre,$tipo,$talla,$stock, $datetime, $path);

        $datosTienda = TiendasADO::obtenerInstancia();
        if($datosTienda->ComprobarStock($nombre, $tipo, $talla, $stock)){
            $datosTienda->DescontarStock($nombre, $tipo, $talla, $stock);
            $datosVentas = VentasADO::obtenerInstancia();
            $data = $datosVentas->altaVenta($venta);
        }else{
            $data = false;
        }
        
        return $data;
    }

    //Leer

    public static function ProductosVendidos($fecha = null){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->TraerProductosVendidos($fecha);
        return $data;
    }

    public static function TraerProductoMasVendido(){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->TraerProductoMasVendido();
        return $data;
    }

    public static function TraerVentasPorUsuario($email){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->TraerVentasPorUsuario($email);
        return $data;
    }

    public static function TraerVentasPorProducto($nombre){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->TraerVentasPorProducto($nombre);
        return $data;
    }

    public static function TraerIngresos($fecha = null){
        $datosVentas = VentasADO::obtenerInstancia();
        $data = $datosVentas->TraerNombres($fecha);

        $arrayVentas = [];

        foreach($data as $ventas){
            $datosTienda = TiendasADO::obtenerInstancia();
            $precio = $datosTienda->ObtenerPrecioPorNombre($ventas["nombre"], $ventas["stock"]);
            $arrayNombreIngreso = array(
                "nombre" => $ventas["nombre"],
                "ingreso" => $precio
            );

            $arrayVentas[] = $arrayNombreIngreso;
        }

        return $arrayVentas;
    }

    public static function VerificarVenta($id){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->VerificarVentaPorID($id);
        return $data;
    }

    //Modificar

    public static function ModificarVenta($email,$nombre,$tipo,$talla,$stock, $id){

        $datosVentas = VentasADO::obtenerInstancia();
        $data = $datosVentas->ModificarVenta($email,$nombre,$tipo,$talla,$stock, $id);

        return $data;
    }

    //OTROS

    public static function CargarFoto($foto, $nombre, $tipo,$talla,$email){
        $partesEmail = explode('@', $email);
        $emailSinArroba = $partesEmail[0];

        $rutaTemporal =  $foto->getStream()->getMetadata('uri');
        $nombreImagen = $nombre . "_" . $tipo . "_".$talla . "_" . $emailSinArroba. ".jpg";
        $carpetaDestino = 'db/ImagenesDeVenta/2024/';
        $rutaDestino = $carpetaDestino . $nombreImagen;
        
        if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
            $retorno = $rutaDestino;
        } else {
            $retorno = null;
        }
        return $retorno;
    }

    public static function traerTodo(){
        $datos = VentasADO::obtenerInstancia();
        $data = $datos->traerTodosLosVentas();
        return $data;
    }
    public static function TraerTodoEnCSV(){
        $data = self::traerTodo();
        $filename = "Ventas" . "_". date("d-m-Y").".csv";
        return Ventas::GenerarCSV($filename, $data);
    }

    public static function GenerarCSV($filename, $data){
        $filePath = "db/tablasVentas/" . $filename;

        $file = fopen($filePath, "w");

        fputcsv($file, array_keys($data[0]));
        foreach($data as $pedido){
            fputcsv($file, $pedido);
        }
        fclose($file);

        return $filePath;
    }
}