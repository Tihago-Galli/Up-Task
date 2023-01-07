<aside class="sidebar <?php echo $titulo?>">
<div class="contenedor-sidebar">
<h2>UpTask</h2>


</div>
   

    <nav class="sidebar-nav">
         <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''?>" href="/dashboard">Proyectos</a>
         <a class="<?php echo ($titulo === 'Crear Proyectos') ? 'activo' : ''?>" href="/crear-proyecto">Crear Proyectos</a>
         <a class="<?php echo ($titulo === 'Perfil' || $titulo === 'Password') ? 'activo' : ''?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar-session-mobile">
        <a href="/logout" class="cerrar-session">Cerrar Sessión</a>
    </div>
</aside>
