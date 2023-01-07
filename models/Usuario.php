<?php 

namespace Model;

class Usuario extends ActiveRecord{

protected static $tabla = 'usuarios';

protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

public $id;
public $nombre;
public $email;
public $password;
public $token;
public $confirmado;

public function __construct($args = [])
{
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->password2 = $args['password2'] ?? '';
    $this->password_actual = $args['password_actual'] ?? '';
    $this->password_nuevo = $args['password_nuevo'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;
}

public function validarLogin()  {
    if(!$this->email){
        self::$alertas['error'][] = "Debes ingresar un Email";
    }

    if(!$this->password){
        self::$alertas['error'][] = "Debes ingresar una contraseña";
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        self::$alertas['error'][] = 'El email no es valido';
    }

    return self::$alertas;
}

public function validarNuevaCuenta() {
    if(!$this->nombre){
        self::$alertas['error'][] = "Debes ingresar un nombre";
    }

    if(!$this->email){
        self::$alertas['error'][] = "Debes ingresar un Email";
    }

    if(!$this->password){
        self::$alertas['error'][] = "Debes ingresar una contraseña";
    }

    if(strlen($this->password) < 6){
        self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
    }

    if($this->password !== $this->password2){
        self::$alertas['error'][] = "Las contraseñas no coinciden";
    }

    return self::$alertas;
}

public function nuevoPassword() {
    if(!$this->password_actual){
        self::$alertas['error'][] = "Debe ingresar su contraseña";
    }
    if(!$this->password_nuevo){
        self::$alertas['error'][] = "Debe ingresar su nueva contraseña";
    }
    if(strlen($this->password_nuevo) < 6){
        self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
    }

    return self::$alertas;
}

public function validarPasswordActual(){

    return password_verify($this->password_actual, $this->password);

}

public function validarPerfil()  
{
    if(!$this->nombre){
        self::$alertas['error'][] = "Debes ingresar un nombre";
    }

    if(!$this->email){
        self::$alertas['error'][] = "Debes ingresar un Email";
    }
    return self::$alertas;
}

public function hashPassword() {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
}

public function crearToken(){

    $this->token = uniqid();
}

public function validarEmail(){
 
    if(!$this->email){
        self::$alertas['error'][] = 'Debes ingresar un Email';
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        self::$alertas['error'][] = 'El email no es valido';
    }

    return self::$alertas;
}

public function validarPassword(){

    if(!$this->password){
        self::$alertas['error'][] = "Debes ingresar una contraseña";
    }

    if(strlen($this->password) < 6){
        self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
    }

    return self::$alertas;
}

}

?>