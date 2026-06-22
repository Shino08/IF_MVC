<?php $title = 'Solicitudes de Cotización'; $active_nav = 'cotizaciones'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Solicitudes de Cotización</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Gestiona todas las solicitudes de clientes</p>
                </div>
            </div>
        </header>

    <div class="p-8">

      <!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
  <div class="flex items-center gap-4">
    
    <div class="flex-1">
      <input type="text" placeholder="Buscar por cliente o ID..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
    </div>

    <button class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
      </svg>
      Estado de Solicitud
    </button>

    <button class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"/>
      </svg>
      Prioridad
    </button>

    <button class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center text-gray-700">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
      </svg>
      dd/mm/yyyy
    </button>

    <button class="btn text-white px-6 py-2.5 rounded-lg font-semibold transition-colors flex items-center hover:scale-102 ease-in-out">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
      </svg>
      Actualizar Lista
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
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prioridad</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              
              <?php if (!empty($solicitudes)): ?>
                <?php foreach ($solicitudes as $solicitud): ?>
                  <tr class="hover:bg-gray-50 transition-colors">
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
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                        Media
                      </span>
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

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Mostrando <span class="font-semibold">1</span> a <span class="font-semibold">10</span> de <span class="font-semibold">15</span> solicitudes
          </div>
          <div class="flex space-x-2">
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
              Anterior
            </button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
              Siguiente
            </button>
          </div>
        </div>

      </div>

    </div>
    </main>
</div>
</body>
</html>
