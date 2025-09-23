document.addEventListener('DOMContentLoaded', function() {
    const enlacesSidebar = document.querySelectorAll('.side-menu a');
    let seccionesArray = [
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

    function cambiarSeccion(indice) {
        if (indice >= 0 && indice < seccionesArray.length) {
            ocultarTodasLasSecciones();
            mostrarSeccion(seccionesArray[indice]);
            actualizarEnlaceActivo(indice);
        }
    }

    function ajustarVisibilidadPorCargo() {
        const cargoUsuario = localStorage.getItem('usuarioCargo');
        if (cargoUsuario) {
            const permisos = {
                'Barbero': ['divHorarioReserva', 'divReservas'],
                'Recepcionista': ['divClientes', 'divHorarioReserva', 'divReservas'],
            };

            switch (cargoUsuario) {
                case 'Barbero':
                    seccionesArray = ['divDashboard', 'divHorarioReserva', 'divReservas'];
                    break;
                case 'Recepcionista':
                    seccionesArray = ['divDashboard', 'divClientes', 'divHorarioReserva', 'divReservas'];
                    break;
                case 'Administrador':
                    break;
                default:
                    console.warn('Cargo de usuario desconocido:', cargoUsuario);
            }

            const seccionesPermitidas = permisos[cargoUsuario];

            if (seccionesPermitidas) {
                enlacesSidebar.forEach(enlace => {
                    const seccionObjetivo = enlace.dataset.seccion;
                    if (!seccionesPermitidas.includes(seccionObjetivo)) {
                        enlace.parentElement.style.display = 'none';
                    }
                });
            }
        }
    }

    enlacesSidebar.forEach((enlace, indice) => {
        enlace.addEventListener('click', function(e) {
            e.preventDefault();
            cambiarSeccion(indice);
        });
    });

    cambiarSeccion(0);
    
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

    ajustarVisibilidadPorCargo();
    restaurarEstado();
    
    window.debugSidebar = function() {
        console.log('Secciones disponibles:', seccionesArray);
        console.log('Sección actual:', obtenerSeccionActual());
        console.log('Enlaces encontrados:', enlacesSidebar.length);
    };
});
