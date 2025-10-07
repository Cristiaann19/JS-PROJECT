document.addEventListener("DOMContentLoaded", () => {
    const reservasModal = document.getElementById("reservasModal");
    const formReserva = document.getElementById("formReserva");
    const sistemaToast = new SistemaToast();

    if (!formReserva) return;

    formReserva.addEventListener("submit", function (e) {
        e.preventDefault();

        //Obtener valores del formulario
        const nombre = formReserva.querySelector("#nombreCliente").value.trim();
        const apellidos = formReserva.querySelector("#apellidos").value.trim();
        const telefono = formReserva.querySelector("#telefono").value.trim();
        const correo = formReserva.querySelector("#correoE").value.trim();
        const fechaReserva = formReserva.querySelector("#fecha").value;
        const hora = formReserva.querySelector("#hora").value;
        const idServicio = formReserva.querySelector("#selectServicioModal").value;

        //Dividir apellidos
        let apellidoPaterno = '';
        let apellidoMaterno = '';
        const partes = apellidos.split(' ');
        if (partes.length > 0) apellidoPaterno = partes[0];
        if (partes.length > 1) apellidoMaterno = partes.slice(1).join(' ');

        const datos = {
            nombre,
            apellidoPaterno,
            apellidoMaterno,
            telefono,
            email: correo,
            fechaReserva,
            hora,
            idServicio
        };

        console.log("Datos a enviar:", datos);

        fetch('../../backEnd/controladores/controladorAgregarReserva.php', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        })
        .then(res => res.json())
        .then(respuesta => {
            console.log("Respuesta del backend:", respuesta);

            if (respuesta.success) {
                sistemaToast.mostrar('success', 'Reserva Exitosa', respuesta.mensaje);
                formReserva.reset();

                const modalInstance = bootstrap.Modal.getInstance(reservasModal);
                if (modalInstance) modalInstance.hide();
            } else {
                // ¡Corrección aquí! Mostramos un toast de error.
                sistemaToast.mostrar('error', 'Error en la reserva', respuesta.mensaje || 'No se pudo completar la reserva.');
                console.error("Error en reserva:", respuesta.mensaje);
            }
        })
        .catch(err => {
            sistemaToast.mostrar('error', 'Error de Conexión', 'No se pudo comunicar con el servidor.');
            console.error("Error al enviar la reserva:", err);
        });
    });
});
