<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{

    public static function index(Router $router){
       iniciarSession();

        isAuth();

        $id =  $_SESSION['id'];

        $proyectos = Proyecto::belongTo('propietarioId', $id);

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
            
        ]);
    }

    public static function crear(Router $router){
        iniciarSession();

        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $proyecto = new Proyecto($_POST);

           $alertas = $proyecto->validarProyecto();

           if(empty($alertas)){
            //generar URL unico
            $hash = md5(uniqid());
            $proyecto->url = $hash;
            //almaceanr creador proyecto
            $proyecto->propietarioId = $_SESSION['id'];

            $proyecto->guardar();

            header('Location: /proyecto?id=' . $proyecto->url);
           }
        }

        $router->render('dashboard/crear',[
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router){
        iniciarSession();
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id ){
                    Usuario::setAlerta('error', 'Ese email ya esta registrado');
                    $alertas = $usuario->getAlertas();
                }else{
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();
    
                    $_SESSION['nombre'] = $usuario->nombre;
                }
               
            }
        }
     
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function proyecto(Router $router){
        iniciarSession();

        isAuth();
        $token = $_GET['id'];

        if(!$token) header('Location: /dashboard');
        //Revisar que la persona que lo creo pueda verlo

        $proyecto = Proyecto::where('url', $token);

       

        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dassboard');
        }

        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function cambiarPassword(Router $router){

        iniciarSession();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)){

                $resultado = $usuario->validarPasswordActual();

                if($resultado){
                    $usuario->password = $usuario->password_nuevo;

                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'Contraseña guardada Correctamente');
                        $alertas = $usuario->getAlertas();
                    }

                }else{
                    Usuario::setAlerta('error', 'La contraseña no coincide');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password',[
            'titulo' => 'Perfil',
            'alertas' => $alertas
        ]);
    }

}
?>