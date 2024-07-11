<?php

include_once "models/User.php";
include_once "utils/AutentificadorJWT.php";


class UserController 
{
    public static function Alta($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $uploadedFiles = $request->getUploadedFiles();
        $foto = $uploadedFiles['foto'];
        
        $mail = $parametros['mail'];
        $usuario = $parametros['usuario'];
        $contraseña = $parametros['contraseña'];
        $perfil = $parametros['perfil'];

        if(User::CrearUser($mail, $usuario, $contraseña, $perfil, $foto)){
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        }
        else{
            $payload = json_encode(array("mensaje" => "El usuario NO se pudo crear"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function Login($request, $response, $args){
        //Verifica, contraseña, nombre y estado; Y ademas te crea un token con datos necesarios
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $contraseña = $parametros['contraseña'];

        $listaUsers = User::traerTodosLosUsuarios();
        foreach($listaUsers as $user){
            //var_dump($user["usuario"]);
            //var_dump($usuario);
            if($user["usuario"] == $usuario && $contraseña == $user["clave"]){
                
                $data = array(
                    "id" => $user["id"],
                    "usuario" => $user["usuario"],
                    "perfil" => $user["perfil"],
                );

                $token = AutentificadorJWT::CrearToken($data);
                $payload = json_encode(array('jwt' => $token));
                break;
            }
            else{
                $payload = json_encode(array("error" => "Usuario o contraseña invalido"));
            }
        }

        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
        //$response->withHeader('Content-Type', 'application/json');
    }
    
}