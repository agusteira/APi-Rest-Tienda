<?php

include_once "models/Tiendas.php";

class TiendasController{

    public static function Alta($request, $response, $args){
        $parametros = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $foto = $uploadedFiles['foto'];

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $color = $parametros['color'];
        $stock = $parametros['stock'];
        
        if(Tiendas::CrearTienda($nombre, $precio,$tipo,$talla,$color,$stock, $foto)){
            $payload = json_encode(array("mensaje" => "Tienda creada con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "La Tienda NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function Consultar($request, $response, $args){
        $parametros = $request->getQueryParams();

        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $color = $parametros['color'];
        if(Tiendas::ComprobarRegistro($nombre, $tipo,$color)){
            $payload = json_encode(array("mensaje" => "existe"));
        }
        else{
            $payload = json_encode(array("mensaje" => "no hay producto del nombre " . $nombre . " y tipo " . $tipo));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ProductosEntreValores($request, $response, $args){
        $parametros = $request->getQueryParams();

        $valor1 = $parametros['valor1'];
        $valor2 = $parametros['valor2'];
        $ProductosEntreValores = Tiendas::ObtenerProductosEntreValores($valor1,$valor2);
        $payload = json_encode(array("ProductosEntreValores" => $ProductosEntreValores));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}