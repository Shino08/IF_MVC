<?php $title = 'Pedidos y Carritos'; $active_nav = 'carritos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pedidos y Carritos</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Gestiona todas las solicitudes de clientes</p>
                </div>
            </div>
        </header>

    <div class="p-8">

      <!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
  <div class="flex flex-wrap items-center gap-4">
    
    <div class="flex-1 min-w-[200px]">
      <input type="text" id="buscador-carritos" placeholder="Buscar por cliente o ID..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
    </div>

    <select id="filtro-estado-carritos" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 bg-white text-gray-700">
      <option value="">Todos los estados</option>
      <?php
      $estados = array_unique(array_map(fn($s) => $s['estado_nombre'] ?? '', $solicitudes ?? []));
      sort($estados);
      foreach ($estados as $est): ?>
        <option value="<?= htmlspecialchars($est) ?>"><?= htmlspecialchars(ucfirst($est)) ?></option>
      <?php endforeach; ?>
    </select>

    <input type="date" id="filtro-fecha-carritos" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700">

    <button type="button" id="btn-limpiar-filtros" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center font-semibold text-gray-700 shadow-sm">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
      </svg>
      Limpiar
    </button>

  </div>
</div>


      <!-- Table Section -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Table Header -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Solicitud</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cliente (Email)</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teléfono</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Solicitud</th>

                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              
              <?php if (!empty($solicitudes)): ?>
                <?php foreach ($solicitudes as $solicitud): ?>
                  <tr class="hover:bg-gray-50 transition-colors"
                      data-estado="<?= htmlspecialchars(strtolower($solicitud['estado_nombre'] ?? '')) ?>"
                      data-fecha="<?= date('Y-m-d', strtotime($solicitud['fecha_solicitud'])) ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-semibold text-gray-900">#COT<?= str_pad((string)$solicitud['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900"><?= htmlspecialchars($solicitud['cliente_nombre'] . ' ' . $solicitud['cliente_apellido']) ?></div>
                      <div class="text-sm text-gray-500"><?= htmlspecialchars($solicitud['cliente_email']) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-700"><?= htmlspecialchars($solicitud['cliente_telefono'] ?? 'N/A') ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-700"><?= date('Y-m-d', strtotime($solicitud['fecha_solicitud'])) ?></span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php 
                        if($solicitud['estado_id'] == 2) echo 'bg-yellow-100 text-yellow-800';
                        elseif($solicitud['estado_id'] == 3) echo 'bg-green-100 text-green-800';
                        else echo 'bg-red-100 text-red-800'; 
                      ?>">
                        <?= htmlspecialchars(ucfirst($solicitud['estado_nombre'])) ?>
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <a href="<?= $base_url ?? '' ?>/dashboard/detalle-solicitud/<?= $solicitud['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Ver
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    No hay solicitudes de cotización.
                  </td>
                </tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>

        <!-- Pagination (dinámica) -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between" id="pagination-bar">
          <div class="text-sm text-gray-700" id="pagination-info">
            Mostrando <span class="font-semibold" id="mostrando-desde">0</span> solicitudes
          </div>
        </div>

      </div>

    </div>
    </main>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscador-carritos');
    const filtroEst = document.getElementById('filtro-estado-carritos');
    const filtroFecha = document.getElementById('filtro-fecha-carritos');
    const btnLimpiar = document.getElementById('btn-limpiar-filtros');
    const rows = document.querySelectorAll('tbody tr');
    const infoEl = document.getElementById('mostrando-desde');

    function filtrar() {
        const texto = (buscador?.value ?? '').toLowerCase().trim();
        const estado = (filtroEst?.value ?? '').toLowerCase().trim();
        const fecha = filtroFecha?.value ?? '';

        let visibles = 0;
        rows.forEach(row => {
            const id = (row.querySelector('td:first-child .font-semibold')?.textContent ?? '').toLowerCase();
            const cliente = (row.querySelector('td:nth-child(2) .text-gray-900')?.textContent ?? '').toLowerCase();
            const rowEstado = (row.dataset.estado ?? '').toLowerCase().trim();
            const rowFecha = (row.dataset.fecha ?? '').trim();

            const matchTexto = !texto || id.includes(texto) || cliente.includes(texto);
            const matchEstado = !estado || rowEstado === estado;
            const matchFecha = !fecha || rowFecha === fecha;

            const show = matchTexto && matchEstado && matchFecha;
            row.style.display = show ? '' : 'none';
            if (show) visibles++;
        });

        infoEl.textContent = visibles;
    }

    function limpiarFiltros() {
        if (buscador) buscador.value = '';
        if (filtroEst) filtroEst.value = '';
        if (filtroFecha) filtroFecha.value = '';
        filtrar();
    }

    buscador?.addEventListener('input', filtrar);
    filtroEst?.addEventListener('change', filtrar);
    filtroFecha?.addEventListener('change', filtrar);
    btnLimpiar?.addEventListener('click', limpiarFiltros);
    filtrar();
});
</script>
</body>
</html>
