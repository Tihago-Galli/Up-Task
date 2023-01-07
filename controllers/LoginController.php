<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;
class LoginController {

    public static function login(Router $router){

        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

          

            if(empty($alertas)){
                //verificamos el usuario
                $usuario = Usuario::where('email', $auth->email);

        
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario no exite o no esta confirmado');
                        
                }else{
                        //comprobar password
                        $resultado = password_verify($_POST['password'], $usuario->password);



                        if(password_verify($_POST['password'], $usuario->password)){  
            
                                session_start();
                                $_SESSION['id'] = $usuario->id;
                                $_SESSION['nombre'] = $usuario->nombre;
                                $_SESSION['email'] = $usuario->email;
                                $_SESSION['login'] = true;

                                header('location: /dashboard');
                        }else{
                        
                            Usuario::setAlerta('error', 'Contraseña incorrecta');
                        }
                }
            }

        }
            $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas,
           
        ]);
    }

    public static function logout(){
      iniciarSession();

      $_SESSION = [];

      header('location: /');

    }


    public static function crear(Router $router){

        $usuario = new Usuario;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            $existeUsuario = Usuario::where('email', $usuario->email);

            if(empty($alertas)){
                if($existeUsuario){
                    Usuario::setAlerta('error', 'Eñ usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                }else{
                    //crear un nuevo usuario
                    $usuario->hashPassword();

                    //eliminar password2
                     unset($usuario->password2);

                     $usuario->crearToken();

                    $resultado = $usuario->guardar();

                    //enviar Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarEmail();

                    if($resultado){
                        header('location: /mensaje');
                    }

                }
            }
           
        }

        $router->render('auth/crear',[
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){

        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario = new Usuario($_POST);

            
            $alertas = $usuario->validarEmail();
     

           if(empty($alertas)){

                $usuario = Usuario::where('email', $usuario->email);
            
               if($usuario && $usuario->confirmado === '1'){

                $usuario->crearToken();
                unset($usuario->password2);
                $usuario->guardar();

                $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                $email->restablecerPassword();
                Usuario::setAlerta('exito','Se ha enviado un email a tu correo');


               }else{

                Usuario::setAlerta('error', 'El usuario no existe');
               }
           }

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
            'titulo' => 'Recuperar Cuenta',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router){

        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token) header('Location: /');

       
         // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $mostrar = false;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //añadir el nuevo password
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPassword();

            if(empty($alertas)){

                $usuario->token = '';
                $usuario->hashPassword();
                $usuario->guardar();
                $alertas = Usuario::setAlerta('exito','Contraseña restablecida');
                $mostrar = false;
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer',[
            'titulo' => 'Restablecer',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo' => 'Mensaje'
        ]);

    }
    public static function confirmar(Router $router){
        
        $token = s($_GET['token']);
        $alertas = [];
        if(!$token) header('Location: /');

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){

            Usuario::setAlerta('error', 'Token no valido');
        }else{
                $usuario->confirmado = 1;
                $usuario->token = '';
                unset($usuario->password2);

                $usuario->guardar();

                Usuario::setAlerta('exito', 'Cuenta Creada Exitosamente');
        }
            $alertas = Usuario::getAlertas();
            $router->render('auth/confirmar',[
            'titulo' => 'Cuenta Confirmada',
            'alertas' => $alertas
        ]);

    }
}

?>

