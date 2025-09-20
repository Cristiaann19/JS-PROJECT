document.addEventListener("DOMContentLoaded", () => {
  const cargoSelect = document.getElementById("cargo");
  const extraCampos = document.getElementById("extraCampos");

  cargoSelect.addEventListener("change", () => {
    const cargo = cargoSelect.value;
    extraCampos.innerHTML = "";

    if(cargo === "Barbero") {
      extraCampos.innerHTML = `
        <label for="especialidad" class="form-label fw-bold">Especialidad</label>
        <input type="text" class="form-control" id="especialidad" name="especialidad" required>
      `;
    } else if(cargo === "Recepcionista") {
      extraCampos.innerHTML = `
        <label for="turno" class="form-label fw-bold">Turno</label>
        <select class="form-select" id="turno" name="turno" required>
          <option value="" selected disabled>Seleccione turno...</option>
          <option value="Mañana">Mañana</option>
          <option value="Tarde">Tarde</option>
          <option value="Noche">Noche</option>
        </select>
      `;
    }
  });
});