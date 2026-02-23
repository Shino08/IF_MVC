document.addEventListener('DOMContentLoaded', function() {

    // --- Referencias al DOM del Formulario ---
    const form = document.getElementById('form-categoria');
    const inputId = document.getElementById('categoria_id');
    const inputNombre = document.getElementById('nombre_categoria');
    const btnTextCat = document.getElementById('btn-text-cat');
    const btnCancelEdit = document.getElementById('btn-cancel-edit');
    const buscador = document.getElementById('buscador-categorias');

    // --- Referencias al DOM del Modal ---
    const modal = document.getElementById('custom-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const btnModalCancel = document.getElementById('btn-modal-cancel');
    const btnModalConfirm = document.getElementById('btn-modal-confirm');
    const modalBtnText = document.getElementById('modal-btn-text');
    const modalIconBg = document.getElementById('modal-icon-bg');
    
    let deleteCategoryId = null; // Variable global para guardar qué vamos a eliminar

    // ==========================================
    // SISTEMA DE MODAL PERSONALIZADO
    // ==========================================
    
    // Función para abrir el modal dinámicamente
// Función para abrir el modal dinámicamente
    function showModal(title, message, type = 'confirm') {
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        if (type === 'confirm') {
            btnModalCancel.style.display = 'block'; // Usar style.display en lugar de classList
            modalBtnText.textContent = 'Sí, eliminar';
            btnModalConfirm.className = 'px-5 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-md flex items-center';
            modalIconBg.className = 'flex-shrink-0 w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center';
        } else if (type === 'error') {
            btnModalCancel.style.display = 'none'; // Usar style.display
            modalBtnText.textContent = 'Entendido';
            btnModalConfirm.className = 'px-5 py-2.5 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition-colors shadow-md flex items-center';
            modalIconBg.className = 'flex-shrink-0 w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center';
        }

        // Mostrar el modal forzando el display flex en línea
        modal.style.display = 'flex';
    }

    // Función para cerrar el modal
    function hideModal() {
        modal.style.display = 'none'; // Ocultar forzando display none
        deleteCategoryId = null; 
    }

    // Evento para el botón cancelar del modal
    if (btnModalCancel) btnModalCancel.addEventListener('click', hideModal);

    // ==========================================
    // 1. CARGAR DATOS PARA EDICIÓN
    // ==========================================
    const botonesEditar = document.querySelectorAll('.btn-editar-cat');
    botonesEditar.forEach(boton => {
        boton.addEventListener('click', function() {
            inputId.value = this.getAttribute('data-id');
            inputNombre.value = this.getAttribute('data-nombre');
            inputNombre.focus();
            
            btnTextCat.textContent = 'Actualizar Categoría';
            btnCancelEdit.classList.remove('hidden');
        });
    });

    // ==========================================
    // 2. CANCELAR EDICIÓN
    // ==========================================
    if (btnCancelEdit) {
        btnCancelEdit.addEventListener('click', function() {
            inputId.value = '';
            inputNombre.value = '';
            btnTextCat.textContent = 'Guardar Categoría';
            btnCancelEdit.classList.add('hidden');
        });
    }

    // ==========================================
    // 3. BUSCADOR EN TIEMPO REAL
    // ==========================================
    if (buscador) {
        buscador.addEventListener('keyup', function(e) {
            const text = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tabla-categorias tbody tr');
            
            rows.forEach(row => {
                const col = row.querySelector('.cat-nombre-td');
                if (col) {
                    row.style.display = col.textContent.toLowerCase().includes(text) ? '' : 'none';
                }
            });
        });
    }

    // ==========================================
    // 4. GUARDAR / EDITAR (Envío por AJAX)
    // ==========================================
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const spinner = document.getElementById('btn-spinner-cat');
            const alertBox = document.getElementById('ajax-alert');
            const alertText = document.getElementById('ajax-alert-text');
            
            const originalText = btnTextCat.textContent;
            btnTextCat.textContent = 'Procesando...';
            spinner.classList.remove('hidden');
            alertBox.className = 'hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                const result = await response.json();
                
                if (result.success) {
                    window.location.reload(); 
                } else {
                    alertBox.classList.remove('hidden');
                    alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                    alertText.textContent = result.error;
                    btnTextCat.textContent = originalText;
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                alertBox.classList.remove('hidden');
                alertBox.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                alertText.textContent = 'Error de conexión con el servidor.';
                btnTextCat.textContent = originalText;
                spinner.classList.add('hidden');
            }
        });
    }

    // ==========================================
    // 5. PREPARAR ELIMINACIÓN (Abre el Modal)
    // ==========================================
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-cat');
    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function() {
            deleteCategoryId = this.getAttribute('data-id');
            showModal(
                'Confirmar Eliminación', 
                '¿Estás seguro de que deseas eliminar esta categoría? Si existen productos asignados a ella, el sistema bloqueará la acción por seguridad.',
                'confirm'
            );
        });
    });

    // ==========================================
    // 6. CONFIRMAR ELIMINACIÓN (Ejecuta AJAX)
    // ==========================================
    if (btnModalConfirm) {
        btnModalConfirm.addEventListener('click', async function() {
            if (!deleteCategoryId) {
                hideModal();
                return;
            }

            const idToProcess = deleteCategoryId;
            modalBtnText.textContent = 'Procesando...';
            btnModalConfirm.disabled = true;
            btnModalConfirm.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                // 🚀 EL TRUCO DEFINITIVO: Tomamos la ruta exacta del formulario y cambiamos 'store' por 'delete'
                const formAction = document.getElementById('form-categoria').action;
                const deleteUrl = formAction.replace('/store', '/delete');

                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: `id=${idToProcess}`
                });
                
                const responseText = await response.text();
                let result;

                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error("💥 ERROR PHP AL ELIMINAR:\n", responseText);
                    throw new Error("El servidor no devolvió un JSON válido.");
                }
                
                if (result.success) {
                    window.location.reload();
                } else {
                    btnModalConfirm.disabled = false;
                    btnModalConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
                    showModal('No se pudo eliminar', result.error, 'error');
                }
            } catch (error) {
                btnModalConfirm.disabled = false;
                btnModalConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
                showModal('Error de Conexión', 'Ocurrió un error. Revisa la consola (F12) para ver el detalle exacto.', 'error');
            }
        });
    }

});