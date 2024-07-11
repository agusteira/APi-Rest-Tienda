<?php


require_once '../vendor/autoload.php';
require_once "controllers/VentasController.php";
require_once "controllers/TiendaController.php";
require_once "controllers/UserController.php";
require_once 'middlewares/ParamMiddlewares.php';
require_once "utils/ConfirmarPerfil.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;


$app = AppFactory::create();
$app->addBodyParsingMiddleware();


$app->group('/tienda', function (RouteCollectorProxy $group) {
    $group->post('/alta', \TiendasController::class . ':Alta')->add(\ParamMiddlewares::class . ':AltaTienda')->add(new ConfirmarPerfil(['admin']));; 
    $group->get('/consultar', \TiendasController::class . ':Consultar')->add(\ParamMiddlewares::class . ':TiendaConsultar'); 
});

$app->group('/ventas', function (RouteCollectorProxy $group) {
    $group->post('/alta', \VentasController::class . ':Alta')->add(\ParamMiddlewares::class . ':AltaVentas')->add(new ConfirmarPerfil(['admin', 'empleado'])); 
    $group->put('/modificar', \VentasController::class . ':Modificar')->add(\ParamMiddlewares::class . ':ModificarVenta')->add(new ConfirmarPerfil(['admin']));
    $group->get('/descargar', \VentasController::class . ':Descargar')->add(new ConfirmarPerfil(['admin']));

    $group->group('/consultar', function (RouteCollectorProxy $subGroup){

        $subGroup->group('/productos', function (RouteCollectorProxy $subSubGroup){
            $subSubGroup->get('/vendidos', \VentasController::class . ':ProductosVendidos')->add(new ConfirmarPerfil(['admin', 'empleado'])); //sin mw
            $subSubGroup->get('/entreValores', \TiendasController::class . ':ProductosEntreValores')->add(\ParamMiddlewares::class . ':ProductosEntreValores'); 
            $subSubGroup->get('/masVendido', \VentasController::class . ':TraerProductoMasVendido')->add(new ConfirmarPerfil(['admin', 'empleado']));  //sin mw
        });

        $subGroup->group('/ventas', function (RouteCollectorProxy $subSubGroup){
            $subSubGroup->get('/porUsuario', \VentasController::class . ':VentasPorUsuario')->add(\ParamMiddlewares::class . ':VentasPorUsuario')->add(new ConfirmarPerfil(['admin', 'empleado'])); 
            $subSubGroup->get('/porProducto', \VentasController::class . ':VentasPorProducto')->add(\ParamMiddlewares::class . ':VentasPorProducto')->add(new ConfirmarPerfil(['admin', 'empleado']));
            $subSubGroup->get('/ingresos', \VentasController::class . ':Ingresos')->add(new ConfirmarPerfil(['admin'])); //sin mw
        });
    })->add(new ConfirmarPerfil(['admin', 'empleado']));
});

$app->post('/registro', \UserController::class . ':Alta')->add(\ParamMiddlewares::class . ':AltaUsuario'); 
$app->post('/login', \UserController::class . ':Login')->add(\ParamMiddlewares::class . ':Login'); 


$app->run();
?>
