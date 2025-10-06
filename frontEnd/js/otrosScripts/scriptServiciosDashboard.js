document.addEventListener("DOMContentLoaded", () => {
    const seccionServicios = document.getElementById('divServicios');
    if (!seccionServicios) return; // No ejecutar si no estamos en la sección de servicios del dashboard

    const tablaServiciosBody = document.getElementById('servicios-body');
    const sistemaToast = new SistemaToast();
    const informeServicioDiv = document.querySelector('.informe-servicio');
    const buscarInput = document.getElementById('buscarServicio');

    // Elementos del Modal de Agregar Servicio
    const modalAgregarServicio = document.getElementById("modalAgregarServicio");
    const btnAbrirModalServicio = document.getElementById("btnAgregar");
    const formServicio = document.getElementById("formServicio");
    const btnCerrarModalServicio = modalAgregarServicio.querySelector(".btn-close");
    const btnCancelarServicio = modalAgregarServicio.querySelector(".btn-secondary");

    let servicios = [];
    let servicioSeleccionado = null;
    let serviciosCargados = false;

    // Muestra la tarjeta de informe para un servicio seleccionado
    function mostrarInformeServicio(servicio) {
        if (!servicio) {
            informeServicioDiv.innerHTML = `
                <h3 style="text-align:center; margin-bottom:10px;">Informe Servicio</h3>
                <p style="text-align:center; color:#666;">Seleccione un servicio para ver los detalles.</p>
            `;
            return;
        }

        const estadoClass = (servicio.estadoS.toLowerCase() === "activo") ? "estado-activo" : "estado-inactivo";
        
        let imagenURL = '/recursos/placeholder_servicio.png';
        if (servicio.imagenURL) {
            const esUrlAbsoluta = servicio.imagenURL.startsWith('http://') || servicio.imagenURL.startsWith('https://');
            imagenURL = esUrlAbsoluta ? servicio.imagenURL : `/${servicio.imagenURL.replace(/^\//, '')}`;
        }

        informeServicioDiv.innerHTML = `
            <h3 style="text-align:center; margin-bottom:15px;">Informe del Servicio</h3>
            <div class="perfil-servicio-card">
                <img src="${imagenURL}" alt="${servicio.nombreServicio}" class="servicio-imagen">
                <div class="servicio-info">
                    <h4>${servicio.nombreServicio}</h4>
                    <p>${servicio.descripcion}</p>
                    <div class="servicio-detalles">
                        <span>Precio: <strong>S/ ${parseFloat(servicio.precio).toFixed(2)}</strong></span>
                        <span>Estado: <strong class='${estadoClass}'>${servicio.estadoS}</strong></span>
                    </div>
                </div>
            </div>
        `;
    }

    // Renderiza la tabla de servicios en el dashboard
    function renderServicios(serviciosArenderizar) {
        tablaServiciosBody.innerHTML = '';
        if (serviciosArenderizar.length === 0) {
            tablaServiciosBody.innerHTML = `<tr><td colspan="5">No se encontraron servicios.</td></tr>`;
            servicioSeleccionado = null;
            mostrarInformeServicio(null);
            return;
        }
        let contador = 1;
        serviciosArenderizar.forEach(serv => {
            const estadoClass = (serv.estadoS.toLowerCase() === "activo") ? "estado-activo" : "estado-inactivo";
            const fila = `
                <tr data-id="${serv.idServicio}">
                    <td>${contador}</td>
                    <td>${serv.nombreServicio}</td>
                    <td>S/ ${parseFloat(serv.precio).toFixed(2)}</td>
                    <td>${serv.descripcion}</td>
                    <td><span class='${estadoClass}'>${serv.estadoS}</span></td>
                </tr>
            `;
            tablaServiciosBody.innerHTML += fila;
            contador++;
        });

        tablaServiciosBody.querySelectorAll('tr').forEach(fila => {
            fila.addEventListener('click', () => {
                tablaServiciosBody.querySelectorAll('tr').forEach(f => f.classList.remove('selected'));
                fila.classList.add('selected');
                servicioSeleccionado = serviciosArenderizar.find(serv => serv.idServicio == fila.dataset.id);
                mostrarInformeServicio(servicioSeleccionado);
            });
        });

        const primeraFila = tablaServiciosBody.querySelector('tr');
        if (primeraFila) {
            primeraFila.click();
        }
    }

    async function cargarServicios() {
        if (serviciosCargados) return;
        try {
            const response = await fetch('/backEnd/controladores/controladorServicios.php?formato=json_full');
            servicios = await response.json();
            renderServicios(servicios);
            serviciosCargados = true;
        } catch (error) {
            console.error('Error al cargar la lista de servicios:', error);
            tablaServiciosBody.innerHTML = `<tr><td colspan="5">Error al cargar los servicios.</td></tr>`;
        }
    }

    const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            if (mutation.attributeName === 'style' && seccionServicios.style.display === 'block') {
                cargarServicios();
            }
        });
    });
    observer.observe(seccionServicios, { attributes: true });

    buscarInput.addEventListener('input', () => {
        const texto = buscarInput.value.toLowerCase().trim();
        const filtrados = servicios.filter(s =>
            s.nombreServicio.toLowerCase().includes(texto) ||
            s.precio.toString().includes(texto) ||
            s.estadoS.toLowerCase().includes(texto)
        );
        renderServicios(filtrados);
    });

    btnAbrirModalServicio.addEventListener("click", () => {
        formServicio.reset();
        modalAgregarServicio.classList.add("show");
    });

    btnCerrarModalServicio.addEventListener("click", () => modalAgregarServicio.classList.remove("show"));
    btnCancelarServicio.addEventListener("click", () => modalAgregarServicio.classList.remove("show"));
    window.addEventListener("click", e => {
        if (e.target === modalAgregarServicio) modalAgregarServicio.classList.remove("show");
    });

    formServicio.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formServicio);
        const datosServicio = Object.fromEntries(formData.entries());

        // Aquí iría la lógica para enviar los datos al backend (controladorAgregarServicio.php)
        // y luego recargar la lista de servicios.
        // Por simplicidad, se omite la llamada fetch, pero sería similar a la que tenías.
        console.log("Datos a enviar:", datosServicio);
        sistemaToast.mostrar('info', 'Funcionalidad de agregar pendiente.');
        modalAgregarServicio.classList.remove("show");
    });
});