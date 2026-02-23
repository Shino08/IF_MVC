document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. LÓGICA DE PREVISUALIZACIÓN DE IMÁGENES
    // ==========================================
    const fileUpload = document.getElementById('file-upload');
    const previewContainer = document.getElementById('preview-container');
    const placeholder = document.getElementById('image-placeholder');

    if(fileUpload) {
        fileUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Limpiar contenedor
            previewContainer.innerHTML = '';
            
            if (files.length === 0) {
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
                return;
            }

            // Validar máximo 5
            if (files.length > 5) {
                alert('Atención: Solo se permiten 5 imágenes por producto.');
                this.value = ''; // Resetear input
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
                return;
            }

            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');

            // Crear miniaturas
            files.forEach((file, index) => {
                // Validar tipo antes de previsualizar
                if (!file.type.match('image.*')) return;

                const reader = new FileReader();
                reader.onload = function(event) {
                    const isMain = index === 0;
                    // La principal ocupa las 2 columnas (col-span-2)
                    const html = `
                        <div class="${isMain ? 'col-span-2' : 'col-span-1'} relative aspect-square bg-white border ${isMain ? 'border-red-500 shadow-sm' : 'border-gray-200'} rounded-xl p-1 overflow-hidden transition-all hover:scale-[1.02]">
                            ${isMain ? '<span class="absolute top-2 left-2 bg-red-600 text-white text-[8px] font-black px-2 py-0.5 rounded-lg z-10 uppercase shadow-sm">Principal</span>' : ''}
                            <img src="${event.target.result}" class="w-full h-full object-contain rounded-lg">
                        </div>
                    `;
                    previewContainer.insertAdjacentHTML('beforeend', html);
                }
                reader.readAsDataURL(file);
            });
        });
    }

    // ==========================================
    // 2. LÓGICA DE ENVÍO POR AJAX (FORM DATA)
    // ==========================================
    const form = document.getElementById('productoForm');
    
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); 

            const btn = document.getElementById('btn-save');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const alertBox = document.getElementById('ajax-alert');
            const alertText = document.getElementById('ajax-alert-text');

            // Estado de carga visual
            btn.disabled = true;
            btnText.textContent = 'Guardando...';
            btnSpinner.classList.remove('hidden');
            alertBox.className = 'hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border'; 

            try {
                // FormData captura todo (texto e imágenes) automáticamente
                const formData = new FormData(form);
                        
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json' 
                    }
                });

                // Convertir respuesta a JSON
                const responseText = await response.text();
                let result;
                
                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error("💥 ERROR PHP:", responseText);
                    throw new Error("El servidor no devolvió un JSON válido.");
                }

                if (result.success) {
                    // Mostrar éxito
                    alertBox.classList.remove('hidden');
                    alertBox.classList.add('bg-green-50', 'border-green-200', 'text-green-700');
                    alertText.textContent = result.message;
                            
                    // Scroll arriba y redirigir
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                } else {
                    // Mostrar error de validación (Ej: SKU duplicado)
                    alertBox.classList.remove('hidden');
                    alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                    alertText.textContent = result.error;
                            
                    btn.disabled = false;
                    btnText.textContent = 'Guardar Producto';
                    btnSpinner.classList.add('hidden');
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

            } catch (error) {
                console.error('❌ Error Fetch:', error);
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                alertText.textContent = 'Hubo un error de conexión con el servidor. Revisa la consola.';
                        
                btn.disabled = false;
                btnText.textContent = 'Guardar Producto';
                btnSpinner.classList.add('hidden');
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});