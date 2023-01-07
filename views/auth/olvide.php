<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu Email para recuperar tu cuenta!</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'?>
        <form action="/olvide" method="POST" class="formulario">
    
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>
            
            <input type="submit" class="boton" value="Recuperar">
        </form>
        <div class="acciones">
           <p>¿Ya tienes cuenta?<a href="/"> Iniciar Sesion</a></p>
           <p>¿Aun no tienes una cuenta?<a href="/crear"> Crear Cuenta</a></p>
        </div>
    </div><!--Contenedor-sm-->
</div>