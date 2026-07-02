/**
 * servicioForm.js — Formulario unificado Agregar / Editar.
 * Sin alert(). Todos los avisos usan modales.
 */
document.addEventListener('DOMContentLoaded', function () {

    // ══════════════════════════════════════════════════════════════════
    // MODALES UTILITARIOS
    // ══════════════════════════════════════════════════════════════════

    /** Modal de notificación (éxito o error) */
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>`;
        } else {
            notifIconWrap.className = 'flex items-center justify-center w-12 h-12 rounded-full mx-auto mb-4 bg-red-100';
            notifIconWrap.innerHTML = `<svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>`;
        }

        notifModal.classList.remove('hidden');
        notifModal.classList.add('flex');
    }

    function hideNotif() {
        notifModal.classList.add('hidden');
        notifModal.classList.remove('flex');
    }

    notifClose.addEventListener('click', hideNotif);
    notifModal.addEventListener('click', (e) => { if (e.target === notifModal) hideNotif(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') hideNotif(); });

    /** Modal de confirmación para borrar imagen */
    const confirmImgModal = document.getElementById('confirm-img-modal');
    const confirmImgCancel = document.getElementById('confirm-img-cancel');
    const confirmImgOk = document.getElementById('confirm-img-ok');
    let pendingDeleteFn = null;

    function showConfirmImg(callback) {
        pendingDeleteFn = callback;
        confirmImgModal.classList.remove('hidden');
        confirmImgModal.classList.add('flex');
    }

    function hideConfirmImg() {
        confirmImgModal.classList.add('hidden');
        confirmImgModal.classList.remove('flex');
        pendingDeleteFn = null;
    }

    confirmImgCancel.addEventListener('click', hideConfirmImg);
    confirmImgModal.addEventListener('click', (e) => { if (e.target === confirmImgModal) hideConfirmImg(); });

    confirmImgOk.addEventListener('click', async function () {
        if (!pendingDeleteFn) return;
        const fn = pendingDeleteFn;
        hideConfirmImg();
        await fn();
    });

    // ══════════════════════════════════════════════════════════════════
    // 1. PREVISUALIZACIÓN DE IMÁGENES NUEVAS
    // ══════════════════════════════════════════════════════════════════
    const fileUpload = document.getElementById('file-upload');
    const previewNuevas = document.getElementById('preview-nuevas');
    const placeholder = document.getElementById('image-placeholder');

    if (fileUpload) {
        fileUpload.addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            previewNuevas.innerHTML = '';

            if (files.length === 0) {
                previewNuevas.classList.add('hidden');
                return;
            }

            if (files.length > 5) {
                this.value = '';
                previewNuevas.classList.add('hidden');
                showNotif('Demasiadas imágenes', 'Solo se permiten 5 imágenes por producto.');
                return;
            }

            if (placeholder) placeholder.classList.add('hidden');
            previewNuevas.classList.remove('hidden');

            files.forEach((file, i) => {
                if (!file.type.match('image.*')) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const isMain = i === 0;
                    previewNuevas.insertAdjacentHTML('beforeend', `
                        <div class="${isMain ? 'col-span-2' : 'col-span-1'} relative aspect-square bg-white border ${isMain ? 'border-red-500' : 'border-gray-200'} rounded-xl p-1 overflow-hidden">
                            ${isMain ? `<span class="absolute top-2 left-2 bg-red-600 text-white text-[8px] font-black px-2 py-0.5 rounded-lg z-10 uppercase">${MODO_EDITAR ? 'Nueva Principal' : 'Principal'}</span>` : ''}
                            <img src="${ev.target.result}" class="w-full h-full object-contain rounded-lg">
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // ══════════════════════════════════════════════════════════════════
    // 2. ENVÍO DEL FORMULARIO (crear / actualizar)
    // ══════════════════════════════════════════════════════════════════
    const form = document.getElementById('servicioForm');
    const alertBox = document.getElementById('ajax-alert');
    const alertText = document.getElementById('ajax-alert-text');

    function showInlineAlert(text, tipo) {
        alertBox.className = 'px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border';
        alertBox.classList.add(
            tipo === 'success'
                ? 'bg-green-50' : 'bg-red-50',
            tipo === 'success'
                ? 'border-green-200' : 'border-red-200',
            tipo === 'success'
                ? 'text-green-700' : 'text-red-700',
        );
        alertText.textContent = text;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.getElementById('btn-save');
            const btnLabel = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            btn.disabled = true;
            btnLabel.textContent = MODO_EDITAR ? 'Actualizando...' : 'Guardando...';
            spinner.classList.remove('hidden');
            alertBox.className = 'hidden';

            try {
                const formData = new FormData(form);
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' },
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
                showNotif('Error de conexión', err.message || 'No se pudo contactar al servidor.');
                btn.disabled = false;
                btnLabel.textContent = MODO_EDITAR ? 'Actualizar Servicio' : 'Guardar Servicio';
                spinner.classList.add('hidden');
            }
        });
    }

    // ══════════════════════════════════════════════════════════════════
    // 3. BORRAR IMAGEN EXISTENTE
    // ══════════════════════════════════════════════════════════════════
    document.querySelectorAll('.img-delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tipo = this.dataset.tipo;
            const servicioId = this.dataset.servicioId;
            const imageId = this.dataset.imageId ?? '0';
            const wrapper = tipo === 'principal'
                ? document.getElementById('img-principal-wrapper')
                : document.getElementById(`img-galeria-${imageId}`);

            showConfirmImg(async () => {
                const fd = new FormData();
                fd.append('tipo', tipo);
                fd.append('servicio_id', servicioId);
                fd.append('image_id', imageId);

                try {
                    const res = await fetch(`${BASE_URL}/dashboard/servicios/imagen/borrar`, {
                        method: 'POST',
                        body: fd,
                        headers: { 'Accept': 'application/json' },
                    });
                    const result = await res.json();

                    if (result.success) {
                        if (wrapper) {
                            wrapper.style.transition = 'opacity 0.3s, transform 0.3s';
                            wrapper.style.opacity = '0';
                            wrapper.style.transform = 'scale(0.9)';
                            setTimeout(() => wrapper.remove(), 300);
                        }
                        if (tipo === 'principal') {
                            const pre = document.getElementById('preview-principal-edit');
                            if (pre) pre.remove();
                            if (placeholder) placeholder.classList.remove('hidden');
                        }
                    } else {
                        showNotif('Error al borrar', result.error || 'No se pudo borrar la imagen.');
                    }
                } catch {
                    showNotif('Error de conexión', 'No se pudo contactar al servidor para borrar la imagen.');
                }
            });
        });
    });

    // ══════════════════════════════════════════════════════════════════
    // 4. REEMPLAZAR IMAGEN EXISTENTE
    // ══════════════════════════════════════════════════════════════════
    document.querySelectorAll('.img-replace-input').forEach(input => {
        input.addEventListener('change', async function () {
            if (!this.files[0]) return;

            const tipo = this.dataset.tipo;
            const servicioId = this.dataset.servicioId;
            const imageId = this.dataset.imageId ?? '0';
            const sku = this.dataset.sku;

            const fd = new FormData();
            fd.append('tipo', tipo);
            fd.append('servicio_id', servicioId);
            fd.append('image_id', imageId);
            fd.append('sku', sku);
            fd.append('imagen', this.files[0]); // archivo único, NO array

            try {
                const res = await fetch(`${BASE_URL}/dashboard/servicios/imagen/reemplazar`, {
                    method: 'POST',
                    body: fd,
                    headers: { 'Accept': 'application/json' },
                });

                let result;
                try { result = await res.json(); }
                catch { throw new Error('Respuesta inesperada del servidor.'); }

                if (result.success) {
                    const nuevaUrl = result.nueva_url + '?t=' + Date.now();

                    if (tipo === 'principal') {
                        const img = document.querySelector('#img-principal-wrapper img');
                        if (img) img.src = nuevaUrl;
                        const sidebarImg = document.getElementById('preview-principal-img');
                        if (sidebarImg) sidebarImg.src = nuevaUrl;
                    } else {
                        const img = document.querySelector(`#img-galeria-${imageId} img`);
                        if (img) img.src = nuevaUrl;
                    }

                    showNotif('Imagen actualizada', 'La imagen se reemplazó con éxito.', 'success');
                } else {
                    showNotif('Error al reemplazar', result.error || 'No se pudo reemplazar la imagen.');
                }
            } catch (err) {
                showNotif('Error de conexión', err.message || 'No se pudo contactar al servidor.');
            }

            // Limpiar el input para que el evento change dispare de nuevo si se elige el mismo archivo
            this.value = '';
        });
    });
});
