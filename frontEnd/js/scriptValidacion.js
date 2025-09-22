const LoginForm = document.getElementById('LoginForm');
const toast = new SistemaToast();

if (LoginForm) {
    LoginForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const user = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!user || !password) {
            alert("Atención! Completa todos los datos.");
            console.log("Atención! Completa todos los datos.");
            toast.mostrar('info', 'Campos obligatorios no ingresados', 'Ingrese su usuario y contraseña.');

            return;
        }
        // Petición al backend
        fetch('/backEnd/controladores/controladorUsuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `usuario=${encodeURIComponent(user)}&contrasena=${encodeURIComponent(password)}`
        })
        .then(res => res.json())
        .then(data => {
            console.log("Respuesta del backend:", data);
            alert(JSON.stringify(data)); // Para debug

            if (data.valido && data.estado === "Activo") {
                localStorage.setItem("usuarioNombre", data.usuario);
                localStorage.setItem("usuarioCargo", data.cargo);

                alert(`¡Listo! Bienvenido ${data.usuario} (${data.cargo}).`);
                toast.mostrar('success', '¡Bienvenido!', 'Bienvenido al sistema');

                setTimeout(() => {
                    window.location.href = "/frontEnd/html/dashboard.html";
                }, 2000);
            } else if (data.estado === "Inactivo") {
                alert("Cuenta inactiva: Tu usuario está inhabilitado.");
                toast.mostrar('warning', 'Usuario inactivo', 'Empelado inactivo, no puede ingresar');
            } else {
                alert("Error: Los datos son incorrectos");
                toast.mostrar('error', '¡Error al inciar sesion!', 'Credenciales incorrectas');
            }
        })
        .catch((error) => {
            console.log("Error en fetch:", error);
            alert("Error: No se pudo conectar al servidor.");
        });
    });
}

//PARA CERRAR SESION
document.getElementById('LogOut')?.addEventListener("click", () => {
    localStorage.removeItem("usuarioNombre");
    localStorage.removeItem("usuarioCargo");
    window.location.href = "/frontEnd/html/login.html";
});