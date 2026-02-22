<?php $title = 'Reportes'; $active_nav = 'reportes'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reportes</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Estadísticas y análisis del sistema</p>
                </div>
            </div>
        </header>

      <div class="p-8">

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <h2 class="text-lg font-bold text-gray-900">Generar Reporte</h2>
          
          <div class="grid grid-cols-4 gap-4 mb-4">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Tipo de Reporte</label>
              <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <option>Solicitudes de Cotización</option>
                <option>Ventas</option>
                <option>Productos</option>
                <option>Servicios</option>
                <option>Clientes</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Fecha Inicio</label>
              <input type="date" value="2026-01-01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Fecha Fin</label>
              <input type="date" value="2026-02-06" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
          </div>
        </div>

        <!-- Report Document -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
          
          <!-- Action Buttons -->
          <div class="flex justify-end space-x-3 p-6 border-b border-gray-200">
            <button class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Descargar PDF
            </button>
          </div>

          <!-- Report Content -->
          <div class="p-8" id="report-content">
            
            <!-- Report Header -->
            <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-gray-200">
              <div>
                <div class="h-28 flex items-start justify-start mb-3">
                  <img src="./css/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-24 object-contain">
                </div>
                <p class="text-sm text-gray-600">Guacara, Carabobo, Venezuela</p>
                <p class="text-sm text-gray-600">Teléfono: +58 412-1234567</p>
                <p class="text-sm text-gray-600">info@instalfuego.com</p>
              </div>
              <div class="text-right">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">REPORTE</h1>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Tipo:</span> Solicitudes de Cotización</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Período:</span> 01/01/2026 - 06/02/2026</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Generado:</span> 06 de Febrero, 2026</p>
                <p class="text-sm text-gray-600"><span class="font-semibold">ID:</span> REP-2026-001</p>
              </div>
            </div>

            <!-- Data Table -->
            <div class="mb-8">
              <h2 class="text-lg font-bold text-gray-900 mb-4">Detalle de Solicitudes</h2>
              <table class="w-full">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Categoría</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Monto Est.</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-001</td>
                    <td class="px-4 py-3 text-sm text-gray-600">15/01/2026</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Juan Pérez</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Detección</td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                        Procesada
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Bs. 103.305,60</td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-002</td>
                    <td class="px-4 py-3 text-sm text-gray-600">18/01/2026</td>
                    <td class="px-4 py-3 text-sm text-gray-900">María García</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Extintores</td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                        Procesada
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Bs. 85.420,00</td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-003</td>
                    <td class="px-4 py-3 text-sm text-gray-600">20/01/2026</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Carlos Rodríguez</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Riego Automático</td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                        Pendiente
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Bs. 156.800,00</td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-004</td>
                    <td class="px-4 py-3 text-sm text-gray-600">22/01/2026</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Ana Martínez</td>
                    <td class="px-4 py-3 text-sm text-gray-600">EPP</td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                        Procesada
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Bs. 45.600,00</td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">COT-005</td>
                    <td class="px-4 py-3 text-sm text-gray-600">25/01/2026</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Luis Fernández</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Detección</td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">
                        Rechazada
                      </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">-</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Totals Section -->
            <div class="flex justify-end mb-8">
              <div class="w-96">
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Total Estimado:</span>
                    <span class="font-bold text-gray-900">Bs. 391.125,60</span>
                  </div>
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Procesado:</span>
                    <span class="font-semibold text-green-700">Bs. 234.325,60</span>
                  </div>
                  <div class="flex justify-between py-2 text-sm border-b border-gray-200">
                    <span class="text-gray-600">Monto Pendiente:</span>
                    <span class="font-semibold text-yellow-700">Bs. 156.800,00</span>
                  </div>
                  <div class="flex justify-between py-3 border-t-2 border-gray-300 mt-2">
                    <span class="text-base font-bold text-gray-900">Tasa de Conversión:</span>
                    <span class="text-xl font-bold text-red-700">75%</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notes -->
            <div class="mb-8">
              <h3 class="text-sm font-bold text-gray-900 mb-3">NOTAS:</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                  <li>Este reporte incluye todas las solicitudes del período especificado</li>
                  <li>Los montos estimados son calculados en base a cotizaciones previas similares</li>
                  <li>Las solicitudes pendientes requieren seguimiento</li>
                  <li>Tasa de conversión: (Procesadas / Total) × 100</li>
                </ul>
              </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-6 border-t border-gray-200">
              <p class="text-sm text-gray-600 mb-2">Reporte generado automáticamente por el sistema InstalFuego</p>
              <p class="text-xs text-gray-500">© 2026 InstalFuego. Todos los derechos reservados.</p>
            </div>

          </div>

        </div>

      </div>
    </main>
</div>
</body>
</html>
