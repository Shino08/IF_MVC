/**
 * productos.js — Lógica del listado de productos
 * Maneja el modal de confirmación y la eliminación por AJAX.
 */
document.addEventListener('DOMContentLoaded', function () {

    const modal         = document.getElementById('modal-eliminar');
    const modalNombre   = document.getElementById('modal-nombre-producto');
    const modalError    = document.getElementById('modal-error');
    const btnCancelar   = document.getElementById('btn-cancelar-eliminar');
    const btnConfirmar  = document.getElementById('btn-confirmar-eliminar');
    const btnText       = document.getElementById('btn-eliminar-text');
    const btnSpinner    = document.getElementById('btn-eliminar-spinner');

    let productoIdPendiente = null;
    let cardElement         = null;

    // ── Abrir modal ───────────────────────────────────────────────────
    window.confirmarEliminar = function (id, nombre, cardEl) {
        productoIdPendiente = id;
        cardElement         = cardEl;
        modalNombre.textContent = nombre;
        modalError.classList.add('hidden');
        modalError.textContent = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    // ── Cerrar modal ──────────────────────────────────────────────────
    function cerrarModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        productoIdPendiente = null;
        cardElement         = null;
    }

    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) cerrarModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModal(); });

    // ── Confirmar eliminación ─────────────────────────────────────────
    btnConfirmar.addEventListener('click', async function () {
        if (!productoIdPendiente) return;

        // Estado de carga
        btnConfirmar.disabled = true;
        btnText.textContent   = 'Eliminando...';
        btnSpinner.classList.remove('hidden');
        modalError.classList.add('hidden');

        try {
            const fd = new FormData();
            fd.append('id', productoIdPendiente);

            const res    = await fetch(`${BASE_URL}/dashboard/productos/eliminar`, {
                method: 'POST',
                body:   fd,
                headers: { 'Accept': 'application/json' }
            });
            const result = await res.json();

            if (result.success) {
                // Animar y quitar la card del DOM
                if (cardElement) {
                    cardElement.style.transition = 'opacity 0.3s, transform 0.3s';
                    cardElement.style.opacity    = '0';
                    cardElement.style.transform  = 'scale(0.95)';
                    setTimeout(() => cardElement.remove(), 350);
                }
                cerrarModal();
            } else {
                modalError.textContent = result.error || 'Error al eliminar el producto.';
                modalError.classList.remove('hidden');
            }
        } catch (err) {
            modalError.textContent = 'Error de conexión con el servidor.';
            modalError.classList.remove('hidden');
        } finally {
            btnConfirmar.disabled = false;
            btnText.textContent   = 'Eliminar';
            btnSpinner.classList.add('hidden');
        }
    });
});
