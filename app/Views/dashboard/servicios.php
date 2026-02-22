<?php $title = 'Servicios'; $active_nav = 'servicios'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Servicios</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Administra los servicios disponibles</p>
                </div>
                <button class="inline-flex items-center px-4 py-2 bg-red-700 text-white text-sm font-semibold rounded-lg hover:bg-red-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Nuevo Servicio
                </button>
            </div>
        </header>

    <div class="p-8">

      <div class="grid grid-cols-3 gap-6">

        <!-- Left Column - Add/Edit Service Form -->
        <div class="col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Agregar Servicio</h2>

            <form>
              <!-- Service Name -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Nombre del Servicio <span class="text-red-600">*</span>
                </label>
                <input type="text" placeholder="Ingrese el nombre del servicio" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
              </div>

              <!-- Description -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Descripción
                </label>
                <textarea rows="4" placeholder="Descripción detallada del servicio..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
              </div>

              <!-- Base Price -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Precio <span class="text-red-600">*</span>
                </label>
                <input type="text" placeholder="Ingrese el precio" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
              </div>

              <!-- Submit Button -->
              <button type="submit" class="w-full btn text-white py-3 rounded-lg font-semibold transition-all hover:shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Guardar Servicio
              </button>

            </form>

          </div>
        </div>

        <!-- Right Column - Services List -->
        <div class="col-span-2">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            
            <!-- Header with Search -->
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-bold text-gray-900">Listado de Servicios</h2>
              <input type="text" placeholder="Buscar servicio..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre del Servicio</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Precio Base</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  
                  <!-- Row 1 -->
                  <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                      <span class="text-sm font-semibold text-gray-900">Instalación de Sistemas</span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="text-sm text-gray-600">Instalación completa de sistemas de detección y exti...</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-semibold text-gray-900">$500.00</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center space-x-3">
                        <button class="text-blue-600 hover:text-blue-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                          </svg>
                        </button>
                        <button class="text-red-600 hover:text-red-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 2 -->
                  <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                      <span class="text-sm font-semibold text-gray-900">Mantenimiento Anual</span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="text-sm text-gray-600">Revisión y mantenimiento preventivo anual de equi...</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-semibold text-gray-900">$250.00</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center space-x-3">
                        <button class="text-blue-600 hover:text-blue-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                          </svg>
                        </button>
                        <button class="text-red-600 hover:text-red-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 3 -->
                  <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                      <span class="text-sm font-semibold text-gray-900">Recarga de Extintores</span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="text-sm text-gray-600">Servicio de recarga y certificación de extintores de t...</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-semibold text-gray-900">$80.00</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center space-x-3">
                        <button class="text-blue-600 hover:text-blue-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                          </svg>
                        </button>
                        <button class="text-red-600 hover:text-red-800 transition-colors">
                          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
              <div class="text-sm text-gray-700">
                Mostrando <span class="font-semibold">1</span> a <span class="font-semibold">5</span> de <span class="font-semibold">8</span> servicios
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
