<div class="barra-mobile <?php echo $titulo?>">
    <h1>UpTask</h1>
    <div class="menu">
        <img id="mobile-menu" src="/build/img/menu.png" alt="">
    </div>
</div>

<div class="barra <?php echo $titulo?>">
    <p>Hola: <span><?php echo $_SESSION['nombre'];?></span></p>

    <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
</div>