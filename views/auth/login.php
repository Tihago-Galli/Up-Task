

<div class="contenedor login">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'?>
        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" >
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesion">
        </form>
        <div class="acciones">
           <p>¿Aun no tienes una cuenta?<a href="/crear"> Crear Cuenta</a></p>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div><!--Contenedor-sm-->
</div>

