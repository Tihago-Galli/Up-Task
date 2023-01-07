<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController{

    public static function index(){
        $proyectoId = $_GET['id'];

        if(!$proyectoId) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);

        iniciarSession();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        //Buscamos las tareas que pertenezcan a ese proyecto
        $tareas = Tarea::belongTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);

    }

    public static function crear(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            iniciarSession();
            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId);

        

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la terea'
                ];
                echo json_encode($respuesta);

            }

            //Agregar tarea al  proyecto
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
      
            $respuesta = [
                    'tipo' => 'exito',
                    'id' => $resultado['id'],
                    'mensaje' => 'Tarea creada exitosamente',
                    'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta);


            

        }
    }

    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //Validar proyecto
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);


            iniciarSession();
    
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la terea'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Agregar tarea al  proyecto
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'mensaje' => 'Actualizado correctamente',
                    'proyectoId' => $proyecto->id
            ];
            }
            
            echo json_encode(['respuesta' => $respuesta]);
            
        }
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //Validar proyecto
            $proyecto = Proyecto::where('url',$_POST['proyectoId']);


            iniciarSession();
    
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la terea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);

            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado correctamente',
                'tipo' => 'exito'
            ];

            echo json_encode($resultado);
        }
    }
}

?>
