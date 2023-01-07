<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ .'/../templates/alertas.php'; ?>

    <a class="enlace" href="/perfil">Modificar Usuario</a>

    <form action="/cambiar-password" class="formulario" method="POST">
        <div class="campo">
            <label for="password">Contraseña Actual</label>
            <input type="password"  name="password_actual" placeholder="Contraseña Actual">
        </div>
        <div class="campo">
            <label for="password">Nueva Contraseña</label>
            <input type="password"  name="password_nuevo" placeholder="Contraseña Nueva">
        </div>
        <input class="guardar-perfil" type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>