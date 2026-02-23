document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault(); 

    const form = this;
    const btn = document.getElementById('btn-login');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const alertBox = document.getElementById('ajax-alert');
    const alertText = document.getElementById('ajax-alert-text');

    // Estado de carga
    btn.disabled = true;
    btnText.textContent = 'Verificando...';
    btnSpinner.classList.remove('hidden');
    alertBox.classList.add('hidden'); 

    // Ocultar alertas de PHP si existen
    const phpAlert = document.querySelector('.bg-red-50.border-red-200');
    if(phpAlert && phpAlert.id !== 'ajax-alert') phpAlert.classList.add('hidden');

    try {
        const formData = new FormData(form);
                
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json' 
            }
        });

        const result = await response.json();

        // Reset estilos de alerta
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border';
                
        if (result.success) {
            alertBox.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
            alertText.textContent = result.message;
                    
            // Redirigir dinámicamente según lo que mandó PHP
            setTimeout(() => {
                window.location.href = result.redirect; 
            }, 1000);
        } else {
            alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
            alertText.textContent = result.error;
                    
            btn.disabled = false;
            btnText.textContent = 'Iniciar Sesión';
            btnSpinner.classList.add('hidden');
        }

    } catch (error) {
        console.error('Error:', error);
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border bg-red-50 border-red-200 text-red-700';
        alertText.textContent = 'Hubo un error de conexión con el servidor.';
                
        btn.disabled = false;
        btnText.textContent = 'Iniciar Sesión';
        btnSpinner.classList.add('hidden');
    }
});