/**
 * servicioForm.js — Formulario Agregar / Editar Servicio.
 * - Preview de imagen nueva
 * - Envío AJAX (crear / actualizar)
 * - Borrar imagen existente (con modal de confirmación)
 * - Reemplazar imagen existente
 * - Todos los avisos usan modales (sin alert())
 */
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════════════════════
    //  MODAL NOTIFICACIÓN
    // ══════════════════════════════════════════════════════════════════
    const notifModal = document.getElementById('notif-modal');
    const notifIconWrap = document.getElementById('notif-icon-wrap');
    const notifTitle = document.getElementById('notif-title');
    const notifMessage = document.getElementById('notif-message');
    const notifClose = document.getElementById('notif-close');

    function showNotif(title, message, tipo = 'error') {
        notifTitle.textContent = title;
        notifMessage.textContent = message;
        if (tipo === 'success') {
            notifIconWrap.className = 'flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 bg-green-100';
            notifIconWrap.innerHTML = `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
        } else {
            notifIconWrap.className = 'flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 bg-red-100';
            notifIconWrap.innerHTML = `<svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`;
        }
        notifModal.classList.remove('hidden');
        notifModal.classList.add('flex');
    }

    function hideNotif() {
        notifModal.classList.add('hidden');
        notifModal.classList.remove('flex');
    }

    notifClose?.addEventListener('click', hideNotif);
    notifModal?.addEventListener('click', (e) => { if (e.target === notifModal) hideNotif(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideNotif(); });

    // ══════════════════════════════════════════════════════════════════
    //  MODAL CONFIRMAR BORRAR IMAGEN
    // ══════════════════════════════════════════════════════════════════
    const confirmImgModal = document.getElementById('confirm-img-modal');
    const confirmImgCancel = document.getElementById('confirm-img-cancel');
    const confirmImgOk = document.getElementById('confirm-img-ok');
    let pendingDeleteFn = null;

    function showConfirmImg(fn) {
        pendingDeleteFn = fn;
        confirmImgModal.classList.remove('hidden');
        confirmImgModal.classList.add('flex');
    }

    function hideConfirmImg() {
        confirmImgModal.classList.add('hidden');
        confirmImgModal.classList.remove('flex');
        pendingDeleteFn = null;
    }

    confirmImgCancel?.addEventListener('click', hideConfirmImg);
    confirmImgModal?.addEventListener('click', (e) => { if (e.target === confirmImgModal) hideConfirmImg(); });

    confirmImgOk?.addEventListener('click', async () => {
        if (!pendingDeleteFn) return;
        const fn = pendingDeleteFn;
        hideConfirmImg();
        await fn();
    });

    // ══════════════════════════════════════════════════════════════════
    //  PREVIEW IMAGEN NUEVA
    // ══════════════════════════════════════════════════════════════════
    const fileUpload = document.getElementById('file-upload');
    const previewNueva = document.getElementById('preview-nueva');
    const previewImg = document.getElementById('preview-nueva-img');
    const btnQuitarPrev = document.getElementById('btn-quitar-preview');
    const sidebarPlaceholder = document.getElementById('sidebar-placeholder');
    const sidebarImgWrap = document.getElementById('sidebar-preview-img-wrap');
    const sidebarImg = document.getElementById('sidebar-preview-img');

    fileUpload?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (ev) => {
            // Mini preview debajo del dropzone
            previewImg.src = ev.target.result;
            previewNueva.classList.remove('hidden');

            // Sidebar preview
            if (sidebarPlaceholder) sidebarPlaceholder.classList.add('hidden');

            if (sidebarImgWrap) {
                sidebarImg.src = ev.target.result;
            } else {
                // Crear nodo sidebar si no existe
                const wrap = document.createElement('div');
                wrap.id = 'sidebar-preview-img-wrap';
                wrap.className = 'aspect-square bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4';
                const img = document.createElement('img');
                img.id = 'sidebar-preview-img';
                img.src = ev.target.result;
                img.className = 'w-full h-full object-contain p-2';
                wrap.appendChild(img);
                document.getElementById('sidebar-preview-wrap').prepend(wrap);
            }
        };
        reader.readAsDataURL(file);
    });

    btnQuitarPrev?.addEventListener('click', () => {
        fileUpload.value = '';
        previewImg.src = '';
        previewNueva.classList.add('hidden');
        // Restaurar sidebar placeholder si no hay imagen existente
        if (!MODO_EDITAR || !document.getElementById('img-servicio-wrapper')) {
            if (sidebarPlaceholder) sidebarPlaceholder.classList.remove('hidden');
            document.getElementById('sidebar-preview-img-wrap')?.remove();
        }
    });

    // ══════════════════════════════════════════════════════════════════
    //  ENVÍO DEL FORMULARIO PRINCIPAL
    // ══════════════════════════════════════════════════════════════════
    const form = document.getElementById('servicioForm');
    const alertBox = document.getElementById('ajax-alert');
    const alertText = document.getElementById('ajax-alert-text');

    function showInlineAlert(text, tipo) {
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border';
        alertBox.classList.add(
            tipo === 'success' ? 'bg-green-50' : 'bg-red-50',
            tipo === 'success' ? 'border-green-200' : 'border-red-200',
            tipo === 'success' ? 'text-green-700' : 'text-red-700',
        );
        alertText.textContent = text;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('btn-save');
        const btnLabel = document.getElementById('btn-text');
        const spinner = document.getElementById('btn-spinner');

        btn.disabled = true;
        btnLabel.textContent = MODO_EDITAR ? 'Actualizando...' : 'Guardando...';
        spinner.classList.remove('hidden');
        alertBox.className = 'hidden';

        try {
            const res = await fetch(form.action, {
                method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json' }
            });

            let result;
            try { result = await res.json(); }
            catch { throw new Error('Respuesta inesperada del servidor.'); }

            if (result.success) {
                showInlineAlert(result.message, 'success');
                setTimeout(() => { window.location.href = result.redirect; }, 1500);
            } else {
                showInlineAlert(result.error, 'error');
                btn.disabled = false;
                btnLabel.textContent = MODO_EDITAR ? 'Actualizar Servicio' : 'Guardar Servicio';
                spinner.classList.add('hidden');
            }
        } catch (err) {
            showNotif('Error de conexión', err.message);
            btn.disabled = false;
            btnLabel.textContent = MODO_EDITAR ? 'Actualizar Servicio' : 'Guardar Servicio';
            spinner.classList.add('hidden');
        }
    });

    // ══════════════════════════════════════════════════════════════════
    //  BORRAR IMAGEN EXISTENTE
    // ══════════════════════════════════════════════════════════════════
    const btnDelete = document.getElementById('img-delete-btn');
    if (btnDelete) {
        btnDelete.addEventListener('click', function () {
            const servicioId = this.dataset.servicioId;

            showConfirmImg(async () => {
                const fd = new FormData();
                fd.append('servicio_id', servicioId);

                try {
                    const res = await fetch(`${BASE_URL}/dashboard/servicios/imagen/borrar`, {
                        method: 'POST', body: fd, headers: { 'Accept': 'application/json' }
                    });
                    const result = await res.json();

                    if (result.success) {
                        document.getElementById('img-servicio-wrapper')?.remove();
                        document.getElementById('imagen-actual-section')?.remove();
                        // Restaurar placeholder sidebar
                        document.getElementById('sidebar-preview-img-wrap')?.remove();
                        if (sidebarPlaceholder) sidebarPlaceholder.classList.remove('hidden');
                    } else {
                        showNotif('Error al borrar', result.error || 'No se pudo borrar la imagen.');
                    }
                } catch {
                    showNotif('Error de conexión', 'No se pudo contactar al servidor.');
                }
            });
        });
    }

    // ══════════════════════════════════════════════════════════════════
    //  REEMPLAZAR IMAGEN EXISTENTE
    // ══════════════════════════════════════════════════════════════════
    const replaceInput = document.getElementById('img-replace-input');
    if (replaceInput) {
        replaceInput.addEventListener('change', async function () {
            if (!this.files[0]) return;

            const servicioId = this.dataset.servicioId;
            const codigo = this.dataset.codigo;

            const fd = new FormData();
            fd.append('servicio_id', servicioId);
            fd.append('codigo', codigo);
            fd.append('imagen', this.files[0]);

            try {
                const res = await fetch(`${BASE_URL}/dashboard/servicios/imagen/reemplazar`, {
                    method: 'POST', body: fd, headers: { 'Accept': 'application/json' }
                });

                let result;
                try { result = await res.json(); }
                catch { throw new Error('Respuesta inesperada.'); }

                if (result.success) {
                    const ts = '?t=' + Date.now();
                    // Actualizar img en la galería actual
                    const imgWrapper = document.querySelector('#img-servicio-wrapper img');
                    if (imgWrapper) imgWrapper.src = result.nueva_url + ts;
                    // Actualizar sidebar
                    const sb = document.getElementById('sidebar-preview-img');
                    if (sb) sb.src = result.nueva_url + ts;

                    showNotif('Imagen actualizada', 'La imagen se reemplazó correctamente.', 'success');
                } else {
                    showNotif('Error al reemplazar', result.error || 'No se pudo reemplazar la imagen.');
                }
            } catch (err) {
                showNotif('Error de conexión', err.message);
            }

            this.value = '';
        });
    }
});
