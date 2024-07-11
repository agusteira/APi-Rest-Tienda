<?php

include_once "././db/ADO/TiendasADO.php";

class Tiendas{
    public $_nombre;
    public $_precio;
    public $_tipo;
    public $_talla;
    public $_color;
    public $_stock;
    public $_path;


    public function __construct ($nombre, $precio,$tipo,$talla,$color,$stock,$path){
        $this->_nombre = $nombre;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_talla = $talla;
        $this->_color = $color;
        $this->_stock = $stock;
        $this->_path = $path;
    }
    public static function CrearTienda($nombre, $precio,$tipo,$talla,$color,$stock,$foto){
        $path = self::CargarFoto($foto,$nombre,$tipo);
        $Tienda = new Tiendas($nombre, $precio,$tipo,$talla,$color,$stock, $path);

        $datos = TiendasADO::obtenerInstancia();
        if ($datos->ComprobarExistencia($Tienda)){
            $data = $datos->ActualizarTienda($Tienda);
        }else{
            $data = $datos->altaTienda($Tienda);
        }
        
        return $data;
    }

    public static function ComprobarRegistro($nombre, $tipo,$color){
        $datos = TiendasADO::obtenerInstancia();
        $data = $datos->comprobarRegistro($nombre, $tipo,$color);
        return $data;
    }

    public static function ObtenerProductosEntreValores($valor1, $valor2){
        $datos = TiendasADO::obtenerInstancia();
        $data = $datos->ListarProductosEntreValores($valor1, $valor2);
        return $data;
    }

    public static function CargarFoto($foto, $nombre, $tipo){
        $rutaTemporal =  $foto->getStream()->getMetadata('uri');
        $nombreImagen = $nombre . "_" . $tipo . ".jpg";
        $carpetaDestino = 'db/ImagenesDeRopa/2024/';
        $rutaDestino = $carpetaDestino . $nombreImagen;
        
        if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
            $retorno = $rutaDestino;
        } else {
            $retorno = null;
        }
        return $retorno;
    }
}