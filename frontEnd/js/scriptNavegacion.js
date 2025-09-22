document.addEventListener('DOMContentLoaded', function() {
    const enlacesSidebar = document.querySelectorAll('.side-menu a');
    const seccionesArray = [
        'divDashboard',
        'divEmpleados',  
        'divClientes',
        'divHorarioReserva',
        'divReservas'
    ];

    function ocultarTodasLasSecciones() {
        seccionesArray.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.style.display = 'none';
            }
        });
    }

    function mostrarSeccion(idSeccion) {
        const elemento = document.getElementById(idSeccion);
        if (elemento) {
            elemento.style.display = 'block';
        }
    }

    function actualizarEnlaceActivo(indiceActivo) {
        enlacesSidebar.forEach((enlace, indice) => {
            if (indice === indiceActivo) {
                enlace.classList.add('active');
            } else {
                enlace.classList.remove('active');
            }
        });
    }

    //funcion para cambiar de seccion
    function cambiarSeccion(indice) {
        if (indice >= 0 && indice < seccionesArray.length) {
            ocultarTodasLasSecciones();
            mostrarSeccion(seccionesArray[indice]);
            actualizarEnlaceActivo(indice);
        }
    }

    enlacesSidebar.forEach((enlace, indice) => {
        enlace.addEventListener('click', function(e) {
            e.preventDefault();
            cambiarSeccion(indice);
        });
    });

    cambiarSeccion(0);
    
    function inicializarNavegacionMejorada() {
        const enlacesConData = document.querySelectorAll('.side-menu a[data-section]');
        
        enlacesConData.forEach(enlace => {
            enlace.addEventListener('click', function(e) {
                e.preventDefault();
                const seccionObjetivo = this.getAttribute('data-section');
                
                seccionesArray.forEach(id => {
                    const elemento = document.getElementById(id);
                    if (elemento) {
                        elemento.style.display = 'none';
                    }
                });
                
                const elemento = document.getElementById(seccionObjetivo);
                if (elemento) {
                    elemento.style.display = 'block';
                }
                
                enlacesConData.forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    window.navegarA = function(nombreSeccion) {
        const indice = seccionesArray.indexOf(nombreSeccion);
        if (indice !== -1) {
            cambiarSeccion(indice);
        }
    };
    
    window.obtenerSeccionActual = function() {
        for (let i = 0; i < seccionesArray.length; i++) {
            const elemento = document.getElementById(seccionesArray[i]);
            if (elemento && elemento.style.display !== 'none') {
                return seccionesArray[i];
            }
        }
        return null;
    };
    
    function guardarEstado(indice) {
        localStorage.setItem('seccionActiva', indice.toString());
    }
    
    function restaurarEstado() {
        const estadoGuardado = localStorage.getItem('seccionActiva');
        if (estadoGuardado !== null) {
            const indice = parseInt(estadoGuardado);
            if (indice >= 0 && indice < seccionesArray.length) {
                cambiarSeccion(indice);
                return;
            }
        }
        cambiarSeccion(0);
    }

    const cambiarSeccionOriginal = cambiarSeccion;
    cambiarSeccion = function(indice) {
        cambiarSeccionOriginal(indice);
        guardarEstado(indice);
    };

    restaurarEstado();

    window.debugSidebar = function() {
        console.log('Secciones disponibles:', seccionesArray);
        console.log('Sección actual:', obtenerSeccionActual());
        console.log('Enlaces encontrados:', enlacesSidebar.length);
    };
});
