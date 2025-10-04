document.addEventListener("DOMContentLoaded", () => {
    const clientesBody = document.getElementById('clientes-body');
    const reservasBody = document.getElementById('reservas-body');
    const buscarInput = document.getElementById('buscarCliente');

    let clientes = [];

    //para cargar reservas de un cliente
    function cargarReservas(idCliente) {
        fetch(`../../backEnd/controladores/controladorCliente.php?idCliente=${idCliente}`)
        .then(res => res.json())
        .then(data => {
            if(data.success){
                const reservas = data.reservas;
                if(reservas.length > 0){
                    reservasBody.innerHTML = reservas.map(r => `
                        <tr>
                            <td style="padding:8px;">${r.nombreServicio}</td>
                            <td style="padding:8px;">${r.fechaReserva}</td>
                            <td style="padding:8px;">${r.hora}</td>
                            <td style="padding:8px;">$${r.precio}</td>
                        </tr>
                    `).join('');
                } else {
                    reservasBody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding:10px;">No hay reservas</td></tr>`;
                }
            } else {
                reservasBody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding:10px;">Error: ${data.mensaje}</td></tr>`;
            }
        })
        .catch(err => {
            reservasBody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding:10px;">Error de conexión: ${err}</td></tr>`;
            console.error(err);
        });
    }

    //para cargar clientes
    function renderClientes(clientesFiltrados = null) {
        const lista = clientesFiltrados || clientes;
        clientesBody.innerHTML = lista.map(c => `
            <tr data-id="${c.id}">
                <td style="padding:8px;">${c.nombre}</td>
                <td style="padding:8px;">${c.apellidoP}</td>
                <td style="padding:8px;">${c.apellidoM}</td>
                <td style="padding:8px;">${c.telefono}</td>
                <td style="padding:8px;">${c.email}</td>
            </tr>
        `).join('');

        clientesBody.querySelectorAll('tr').forEach(fila => {
            fila.addEventListener('click', () => {
                clientesBody.querySelectorAll('tr').forEach(r => r.classList.remove('seleccionado'));
                fila.classList.add('seleccionado');
                cargarReservas(fila.dataset.id);
            });
        });

        const primeraFila = clientesBody.querySelector('tr');
        if(primeraFila){
            primeraFila.classList.add('seleccionado');
            cargarReservas(primeraFila.dataset.id);
        }
    }

    //Filtrado
    buscarInput.addEventListener('input', () => {
        const texto = buscarInput.value.toLowerCase();
        const filtrados = clientes.filter(c =>
            c.nombre.toLowerCase().includes(texto) ||
            c.apellidoP.toLowerCase().includes(texto) ||
            c.apellidoM.toLowerCase().includes(texto) ||
            c.telefono.includes(texto) ||
            c.email.toLowerCase().includes(texto)
        );
        renderClientes(filtrados);
    });

    //Traer clientes
    fetch('../../backEnd/controladores/controladorCliente.php')
    .then(res => res.json())
    .then(data => {
        if(data.success){
            clientes = data.clientes;
            renderClientes();
        } else {
            clientesBody.innerHTML = `<tr><td colspan="5">Error al cargar clientes: ${data.mensaje}</td></tr>`;
        }
    })
    .catch(err => {
        clientesBody.innerHTML = `<tr><td colspan="5">Error de conexión: ${err}</td></tr>`;
        console.error(err);
    });
});
