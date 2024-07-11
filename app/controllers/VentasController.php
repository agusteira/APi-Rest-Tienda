<?php

include_once "models/Ventas.php";

class VentasController{

    public static function Alta($request, $response, $args){
        $parametros = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $talla = $parametros['talla'];
        $stock = $parametros['stock'];

        $foto = $uploadedFiles['foto'];

        if(Ventas::CrearVenta($email,$nombre,$tipo,$talla,$stock, $foto)){
            $payload = json_encode(array("mensaje" => "Venta creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "La Venta NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ProductosVendidos($request, $response, $args){
        $parametros = $request->getQueryParams();

        if(isset($parametros['fecha'])){
            $fecha = $parametros['fecha'];
            $ProductosVendidos = Ventas::ProductosVendidos($fecha);
            $payload = json_encode(array("ProductosVendidos" => $ProductosVendidos));
        }
        else{
            $ProductosVendidos = Ventas::ProductosVendidos();
            $payload = json_encode(array("ProductosVendidos" => $ProductosVendidos));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function TraerProductoMasVendido($request, $response, $args){

        $productoMasVendido = Ventas::TraerProductoMasVendido();
        $payload = json_encode(array("productoMasVendido" => $productoMasVendido));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function VentasPorUsuario($request, $response, $args){
        $parametros = $request->getQueryParams();
        $email = $parametros['email'];

        $ventasPorUsuario = Ventas::TraerVentasPorUsuario($email);
        $payload = json_encode(array("ventasPorUsuario" => $ventasPorUsuario));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function VentasPorProducto($request, $response, $args){
        $parametros = $request->getQueryParams();
        $nombre = $parametros['nombre'];

        $ventasPorProducto = Ventas::TraerVentasPorProducto($nombre);
        $payload = json_encode(array("ventasPorProducto" => $ventasPorProducto));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function Ingresos($request, $response, $args){
        $parametros = $request->getQueryParams();

        if(isset($parametros['fecha'])){
            $fecha = $parametros['fecha'];
            $IngresosPorFecha = Ventas::TraerIngresos($fecha);
            $payload = json_encode(array("IngresosPorFecha" => $IngresosPorFecha));
        }
        else{
            $IngresosPorFecha = Ventas::TraerIngresos();
            $payload = json_encode(array("IngresosPorFecha" => $IngresosPorFecha));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public static function Modificar($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        

        if(Ventas::VerificarVenta($id)){
            $email = $parametros['email'];
            $nombre = $parametros['nombre'];
            $tipo = $parametros['tipo'];
            $talla = $parametros['talla'];
            $stock = $parametros['stock'];
            if(Ventas::ModificarVenta($email,$nombre,$tipo,$talla,$stock, $id)){
                $payload = json_encode(array("mensaje" => "Venta modificada con exito"));
            }else{
                $payload = json_encode(array("mensaje" => "la Venta no se pudo modificar"));
            }
        }
        else{
            $payload = json_encode(array("mensaje" => "La Venta NO existe"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function Descargar($request, $response, $args){
        $filePath = Ventas::TraerTodoEnCSV(); //Devuelve un csv
        return $response->withHeader('Content-Type', 'application/csv')
                        ->withHeader('Content-Disposition', 'attachment; filename="' . "Ventas" . "_". date("d-m-Y").".csv" . '"')
                        ->withHeader('Content-Length', filesize($filePath))
                        ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
    }
}
