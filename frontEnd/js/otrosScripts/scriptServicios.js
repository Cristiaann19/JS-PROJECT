document.addEventListener("DOMContentLoaded", () => {
    const seccionServicios = document.getElementById('divServicios');
    const tablaServiciosBody = document.getElementById('servicios-body');
    let serviciosCargados = false;

    function cargarServicios() {
        if (serviciosCargados) return;

        fetch('/backEnd/controladores/controladorServiciosLista.php')
            .then(response => response.text())
            .then(html => {
                tablaServiciosBody.innerHTML = html;
                serviciosCargados = true;
            })
            .catch(error => {
                console.error('Error al cargar la lista de servicios:', error);
                tablaServiciosBody.innerHTML = `<tr><td colspan="5">Error al cargar los servicios.</td></tr>`;
            });
    }

    const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            if (mutation.attributeName === 'style' && seccionServicios.style.display === 'block') {
                cargarServicios();
            }
        });
    });

    observer.observe(seccionServicios, { attributes: true });
});