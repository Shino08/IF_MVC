/**
 * servicios.js — Listado de servicios.
 * Modal de confirmación + eliminación por AJAX + búsqueda/filtrado.
 */
document.addEventListener('DOMContentLoaded', function () {

    // ── Modal eliminar ────────────────────────────────────────────────
    const modal = document.getElementById('modal-eliminar');
    const modalNombre = document.getElementById('modal-nombre-servicio');
    const modalError = document.getElementById('modal-error');
    const btnCancelar = document.getElementById('btn-cancelar-eliminar');
    const btnConfirmar = document.getElementById('btn-confirmar-eliminar');
    const btnText = document.getElementById('btn-eliminar-text');
    const btnSpinner = document.getElementById('btn-eliminar-spinner');

    let idPendiente = null;
    let cardPendiente = null;

    window.confirmarEliminar = function (id, nombre, card) {
        idPendiente = id;
        cardPendiente = card;
        modalNombre.textContent = nombre;
        modalError.classList.add('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    function cerrarModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        idPendiente = cardPendiente = null;
    }

    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) cerrarModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModal(); });

    btnConfirmar.addEventListener('click', async function () {
        if (!idPendiente) return;

        btnConfirmar.disabled = true;
        btnText.textContent = 'Eliminando...';
        btnSpinner.classList.remove('hidden');
        modalError.classList.add('hidden');

        try {
            const fd = new FormData();
            fd.append('id', idPendiente);

            const res = await fetch(`${BASE_URL}/dashboard/servicios/eliminar`, {
                method: 'POST', body: fd, headers: { 'Accept': 'application/json' }
            });
            const result = await res.json();

            if (result.success) {
                if (cardPendiente) {
                    cardPendiente.style.transition = 'opacity 0.3s, transform 0.3s';
                    cardPendiente.style.opacity = '0';
                    cardPendiente.style.transform = 'scale(0.95)';
                    setTimeout(() => cardPendiente.remove(), 350);
                }
                cerrarModal();
            } else {
                modalError.textContent = result.error || 'Error al eliminar.';
                modalError.classList.remove('hidden');
            }
        } catch {
            modalError.textContent = 'Error de conexión con el servidor.';
            modalError.classList.remove('hidden');
        } finally {
            btnConfirmar.disabled = false;
            btnText.textContent = 'Eliminar';
            btnSpinner.classList.add('hidden');
        }
    });

    // ── Búsqueda y filtrado en tiempo real ────────────────────────────
    const buscador = document.getElementById('buscador-servicios');
    const filtroCat = document.getElementById('filtro-categoria');
    const cards = document.querySelectorAll('#grid-servicios [data-card]');

    function filtrar() {
        const texto = (buscador?.value ?? '').toLowerCase().trim();
        const cat = (filtroCat?.value ?? '').toLowerCase().trim();

        cards.forEach(card => {
            const nombre = card.dataset.nombre ?? '';
            const codigo = card.dataset.codigo ?? '';
            const catNom = card.dataset.categoria.toLowerCase();

            const matchTexto = !texto || nombre.includes(texto) || codigo.includes(texto);
            const matchCat = !cat || catNom === cat;

            card.style.display = (matchTexto && matchCat) ? '' : 'none';
        });
    }

    buscador?.addEventListener('input', filtrar);
    filtroCat?.addEventListener('change', filtrar);
});
