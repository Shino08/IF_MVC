document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault(); 

    const form = this;
    const btn = document.getElementById('btn-register');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const alertBox = document.getElementById('ajax-alert');
    const alertText = document.getElementById('ajax-alert-text');

    // ────────────────────────────────────────────────────────
    // VALIDACIÓN FRONTEND: CÉDULA / RIF
    // ────────────────────────────────────────────────────────
    const cedulaInput = document.getElementById('cedula').value.trim().toUpperCase();
    const cedulaRegex = /^([VE]-\d{6,8}|[JVEGPC]-\d{8}-\d)$/;

    if (!cedulaRegex.test(cedulaInput)) {
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border bg-red-50 border-red-200 text-red-700';
        alertText.textContent = 'El formato de Cédula/RIF es inválido. Ejemplos válidos: V-12345678 o J-12345678-9.';
        alertBox.classList.remove('hidden');
        return; // Detenemos la ejecución aquí, no hacemos la petición AJAX
    }

    // Estado de carga
    btn.disabled = true;
    btnText.textContent = 'Procesando...';
    btnSpinner.classList.remove('hidden');
    alertBox.classList.add('hidden'); 

    // Ocultar alertas de PHP si existen
    const phpAlert = document.querySelector('.bg-red-50.border-red-200');
    if(phpAlert && phpAlert.id !== 'ajax-alert') phpAlert.classList.add('hidden');

    try {
        const formData = new FormData(form);
        // Forzamos la cédula a mayúsculas en el form data antes de enviar
        formData.set('cedula', cedulaInput);
                
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json' 
            }
        });

        // Parseo directo a JSON (Código limpio)
        const result = await response.json();

        // Reset estilos de alerta
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border';
                
        if (result.success) {
            alertBox.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
            alertText.textContent = result.message;
                    
            setTimeout(() => {
                window.location.href = BASE_URL + "/login";
            }, 1500);
        } else {
            alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
            alertText.textContent = result.error;
                    
            btn.disabled = false;
            btnText.textContent = 'Crear Cuenta';
            btnSpinner.classList.add('hidden');
        }

    } catch (error) {
        console.error('Error:', error);
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border bg-red-50 border-red-200 text-red-700';
        alertText.textContent = 'Hubo un error de conexión con el servidor.';
                
        btn.disabled = false;
        btnText.textContent = 'Crear Cuenta';
        btnSpinner.classList.add('hidden');
    }
});