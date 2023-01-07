<div class="contenedor restablecer">
    
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'?>
    
        <div class="contenedor-sm">
            <p class="descripcion-pagina">Coloca tu nueva Contraseña</p>
    
            <?php include_once __DIR__ . '/../templates/alertas.php'?>

            <?php if($mostrar): ?>
            <form method="POST" class="formulario">
               
                <div class="campo">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Tu password" name="password">
                </div>
    
                <input type="submit" class="boton" value="Restablecer">
            </form>

            <?php endif; ?>
            <div class="acciones">
               <p>¿Aun no tienes una cuenta?<a href="/crear"> Crear Cuenta</a></p>
                <a href="/olvide">¿Olvidaste tu contraseña?</a>
            </div>
        </div><!--Contenedor-sm-->
    </div>
    
    