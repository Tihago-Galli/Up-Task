(function(){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

async function obtenerTareas(){

    try {
        const id = obtenerProyecto();
        const url = `/api/tareas?id=${id}`
        
        const resultado = await fetch(url);
        const respuesta = await resultado.json();
        
        tareas = respuesta.tareas;
      
    
        mostrarTareas();
        
    } catch (error) {
        console.log(error)
    }
   
}

//filtros de busquedas
const filtros = document.querySelectorAll('#filtros input[type="radio"]');

filtros.forEach(radio =>{
    radio.addEventListener('click', filtrarTareas);
})

function filtrarTareas(e){
    const filtro = e.target.value;

    if(filtradas !== ''){

        filtradas = tareas.filter(tarea => tarea.estado === filtro);
    }else{
        filtradas = [];
    }

    mostrarTareas();
}


function totalPendientes(){
    const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
    const pendientesRadio = document.querySelector('#pendientes');

    if(totalPendientes.length === 0){
        pendientesRadio.disabled = true;
    }else{
        pendientesRadio.disabled = false;
    }

}

function totalCompletas(){
    const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
    const completadasRadio = document.querySelector('#completadas');

    if(totalCompletas.length === 0){
        completadasRadio.disabled = true;
    }else{
        completadasRadio.disabled = false;
    }

   

}


function mostrarTareas(){
    totalPendientes();
    totalCompletas();
   limpiarTareas();


   //verifica si el array de filtradas esta vacia, si esta vacia trae todas las tareas, sino, trae las filtradas
   const arrayTareas = filtradas.length ? filtradas : tareas;
    if(arrayTareas.length === 0){
        const contenedorTareas = document.querySelector('.listado-tareas');
        const listadoTareas = document.createElement('LI');
        listadoTareas.textContent = 'Aun no hay tareas';
        listadoTareas.classList.add('no-tareas');
        contenedorTareas.appendChild(listadoTareas);

        return;
    }

    const estado = {
        0: 'Pendiente',
        1: 'Completado'
    }

    arrayTareas.forEach(tarea =>{
        const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = tarea.id;
        contenedorTarea.classList.add('tarea');

        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = tarea.nombre
        nombreTarea.ondblclick = function (){
            mostrarFormulario(true, {...tarea});
        }

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');

        //botones
        const btnEstado = document.createElement('BUTTON');
        btnEstado.classList.add('estado-tarea');
        btnEstado.classList.add(`${estado[tarea.estado].toLowerCase()}`);
        btnEstado.dataset.estadoTarea = estado[tarea.estado];
        btnEstado.textContent = estado[tarea.estado];
        btnEstado.ondblclick = function(){
            cambiarEstadoTarea({...tarea})
        }

        const btnEliminarTarea = document.createElement('BUTTON');
        btnEliminarTarea.classList.add('eliminar-tarea');
        btnEliminarTarea.dataset.idTarea = tarea.id;
        btnEliminarTarea.textContent = 'Eliminar';
        btnEliminarTarea.ondblclick = function(){
            confirmartarea({...tarea})
        }
            

        opcionesDiv.appendChild(btnEstado);
        opcionesDiv.appendChild(btnEliminarTarea);

        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);

    
        const listadoTareas = document.querySelector('.listado-tareas');
        listadoTareas.appendChild(contenedorTarea);

    })
}

//Boton para agregarel Modal de Agregar tarea
const botonNuevaTarea = document.querySelector('#agregar-tarea');
botonNuevaTarea.addEventListener('click', function(){
    mostrarFormulario(false);
});


function mostrarFormulario(editar = false, tarea = {}){

    const modal = document.createElement('DIV');
    modal.classList.add('modal');
    modal.innerHTML = `
        <form class= "formulario nueva-tarea">
        <legend> ${editar ? 'Editar Tarea' : 'Añade una nueva tarea' }</legend>
        <div class="campo">
        <label>Tarea</label>
        <input type="text" name="tarea" placeholder="${tarea.nombre ? 'Editar Tarea' : 'Añade una tarea al proyecto'}" value = '${tarea.nombre ? tarea.nombre : ''}' id="tarea" />
        </div>
        <div class="opciones">
        <input type="submit" class="submit-nueva-tarea" value="${editar ? 'Editar Tarea' : 'Añadir Tarea'}"/> 
        <button type="button" class="cerrar-modal">Cancelar</button>
        </div>
        </form> `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e){
            e.preventDefault();
            //contains compueba de que una elemento al que le demos click contenga una clase
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
               
            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                  //trim elimina los espacios
                const nombreTarea = document.querySelector('#tarea').value.trim();
                //comprobamos de que la tarea tenga un nombre
                if(nombreTarea === ''){
                    //mostrar alerta de error
                mostrarAlerta('El nombre de la tarea es Obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;
                }

                if(editar){
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
    
       
            }
            
        } )
        document.querySelector('.dashboard').appendChild(modal);
}



function mostrarAlerta(mensaje, tipo, referencia){
    const alertaPrevia = document.querySelector('.alertas');

    if(alertaPrevia){
        alerta.remove();
    }

    const alerta = document.createElement('DIV');
    alerta.classList.add('alertas', tipo);
    alerta.textContent = mensaje;
//inserta la alerta antes del legend
    referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

    setTimeout(() => {
        alerta.remove();
    }, 5000);
}

//consultar el servidor para añadir una nueva tarea
   async function agregarTarea(tarea){
        const datos = new FormData();

        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());



        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

        

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === "exito"){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);

                //agregar el objeto de tarea al globlal de tareas
                const ObjTarea = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: '0',
                    proyectoId: resultado.proyectoId
                }
                //añadimos al arreglo existente el nuevo objeto de tarea
                tareas = [...tareas, ObjTarea];
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
}

function cambiarEstadoTarea(tarea){
    const nuevoEstado = tarea.estado === '1' ? '0' : '1';
    tarea.estado = nuevoEstado;
   actualizarTarea(tarea);
}

function confirmartarea(tarea){
    Swal.fire({
        title: 'Deseas Eliminar la tarea?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No'
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            eliminarTarea(tarea);
        } 
      })
}

async function eliminarTarea(tarea){

    const {estado, id, nombre,} = tarea;

    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('estado', estado);
    datos.append('proyectoId', obtenerProyecto());


    try {
        const url = 'http://localhost:3000/api/tarea/eliminar';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

       if(resultado.resultado){
     
        Swal.fire('Eliminado!', resultado.mensaje, 'success');

     tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id)


         mostrarTareas();
       }
    } catch (error) {
        console.log(error)
    }
}

async function actualizarTarea(tarea){

    
    const {estado, id, nombre} = tarea;

    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('estado', estado);
    datos.append('proyectoId', obtenerProyecto());

    /*Con esto podemos iterar sobre los valores que enviamos
    for(let valor of datos.values()){
        console.log(valor)
    }
    */

try {

    const url = 'http://localhost:3000/api/tarea/actualizar';

    const respuesta = await fetch(url, {
        method: 'POST',
        body: datos
    });

    const resultado = await respuesta.json();

  

    if(resultado.respuesta.tipo === 'exito'){

        Swal.fire('Actualizado!' ,resultado.respuesta.mensaje, 'success');
        
        const modal = document.querySelector('.modal');
        if(modal){
            modal.remove();
        }
    
            tareas = tareas.map(tareaMemoria => {
                if(tareaMemoria.id === id){
                   tareaMemoria.estado = estado,
                   tareaMemoria.nombre = nombre

                }
                return tareaMemoria;
            });
            mostrarTareas();
    }


    
} catch (error) {
    console.log(error)
}
}



function obtenerProyecto(){

        //obtenemos la URL del proyecto
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
}

function limpiarTareas(){
    const listado = document.querySelector('#listado-tareas');
    while(listado.firstChild){
        listado.removeChild(listado.firstChild)
    }
}

})();