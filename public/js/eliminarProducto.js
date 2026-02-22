// <!-- Modal de confirmación de eliminación -->
// <div id="modal-eliminar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
//     <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
//         <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
//             <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
//                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
//             </svg>
//         </div>
//         <h3 class="text-lg font-bold text-gray-900 text-center mb-1">Eliminar producto</h3>
//         <p class="text-gray-500 text-sm text-center mb-6">
//             ¿Eliminar <span id="modal-nombre" class="font-semibold text-gray-700"></span>? Esta acción no se puede deshacer.
//         </p>
//         <div class="flex gap-3">
//             <button onclick="cerrarModal()"
//                     class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
//                 Cancelar
//             </button>
//             <a id="modal-confirmar" href="#"
//                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold text-center transition-colors">
//                 Eliminar
//             </a>
//         </div>
//     </div>
// </div>

// <script>
// const modal = document.getElementById('modal-eliminar');

// function confirmarEliminar(id, nombre) {
//     document.getElementById('modal-nombre').textContent = nombre;
//     document.getElementById('modal-confirmar').href = `<?= $base_url ?>/dashboard/productos/eliminar/${id}`;
//     modal.classList.remove('hidden');
//     modal.classList.add('flex');
// }

// function cerrarModal() {
//     modal.classList.add('hidden');
//     modal.classList.remove('flex');
// }

// // Cerrar con Escape o clic en el fondo
// document.addEventListener('keydown', e => e.key === 'Escape' && cerrarModal());
// modal.addEventListener('click', e => e.target === modal && cerrarModal());
// </script>