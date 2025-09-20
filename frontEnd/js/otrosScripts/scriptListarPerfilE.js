document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.getElementById("empleados-body");
  tbody.addEventListener("click", (e) => {
    const fila = e.target.closest("tr");
    if (fila) {
      const dniCell = fila.querySelector(".dni-empleado");
      if (dniCell) {
        const dni = dniCell.dataset.dni;

        fetch(`/backEnd/controladores/controladorPerfilEmpleado.php?dni=${dni}`)
        .then(res => res.json())
        .then(data => {
            let fotoSrc = data.generoE === "Femenino"
                        ? "/recursos/userMujer.png"
                        : "/recursos/userHombre.png";
            const perfilHTML = `
                <div class="perfil-datos">
                    <h3>${data.nombreEmpleado} ${data.apellidoPaternoE} ${data.apellidoMaternoE}</h3>
                    <p><span class="cargo-empleado">${data.cargo}</span></p>
                    <ul>
                        <li><b>DNI</b><span>${data.dni}</span></li>
                        <li><b>Teléfono</b><span>${data.telefono}</span></li>
                        <li><b>Cargo</b><span>${data.cargo}</span></li>
                        <li><b>Salario</b><span>S/. ${data.salario}</span></li>
                    </ul>
                </div>
            `;
            document.querySelector(".perfil-empleado .perfil-datos").innerHTML = perfilHTML;
            document.getElementById("fotoEmpleado").src = fotoSrc;
            document.getElementById("perfilEmpleado").style.display = "flex";
        })
        .catch(err => alert("Error en fetch: " + err));
      }
    }
  });
});