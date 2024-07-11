<?php

include_once "././db/ADO/UsuariosADO.php";
class User{
    public $_mail;
    public $_usuario;
    public $_contraseña;
    public $_perfil;
    public $_path;
    public $_fechaAlta;

    public function __construct ($mail, $usuario, $contraseña, $perfil, $path, $fechaAlta){
        $this->_mail = $mail;
        $this->_usuario = $usuario;
        $this->_contraseña = $contraseña;
        $this->_perfil = $perfil;
        $this->_path = $path;
        $this->_fechaAlta = $fechaAlta;
    }

    //Crear
    public static function CrearUser($mail, $usuario, $contraseña, $perfil, $foto){
        $datetime = date("Y-m-d H:i:s");
        $date = date("Y-m-d");
        $path = self::CargarFoto($foto,$usuario,$perfil,$date);
        
        $user = new User($mail, $usuario, $contraseña, $perfil, $path, $datetime);


        $datos = UsuariosADO::obtenerInstancia();
        $data = $datos->AltaUsuario($user);
        
        return $data;
    }

    //Leer

    public static function TraerTodosLosUsuarios(){
        $datos = UsuariosADO::obtenerInstancia();
        $data = $datos->traerTodosLosUsuarios();
        return $data;
    }

    //OTROS

    public static function CargarFoto($foto,$usuario,$perfil,$date){

        $rutaTemporal =  $foto->getStream()->getMetadata('uri');
        $nombreImagen = $usuario . "_" . $perfil . "_". $date . ".jpg";
        $carpetaDestino = 'db/ImagenesDeUsuarios/2024/';
        $rutaDestino = $carpetaDestino . $nombreImagen;
        
        if (move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreImagen)) {
            $retorno = $rutaDestino;
        } else {
            $retorno = null;
        }
        return $retorno;
    }
}