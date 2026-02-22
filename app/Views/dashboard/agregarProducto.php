<?php $title = 'Agregar Producto'; $active_nav = 'productos'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    
    <main class="flex-1 overflow-y-auto flex flex-col">

        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <a href="<?= $base_url ?>/dashboard/productos"
                   class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Agregar Nuevo Producto</h1>
                    <p class="text-gray-500 text-sm">Introduce los detalles del nuevo producto</p>
                </div>
            </div>
        </header>

        <!-- Content -->
    <div class="p-8">

      <div class="grid grid-cols-3 gap-6">

        <!-- Left Column - Form -->
        <div class="col-span-2">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Detalles del Producto</h2>

            <form>
              <!-- Product Name -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Nombre del Producto <span class="text-red-600">*</span>
                </label>
                <input type="text" placeholder="Ingrese el nombre del producto" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
              </div>

              <!-- Category -->
                <div class="mb-6">
                  <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Categoría <span class="text-red-600">*</span>
                  </label>
                  <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 flex items-center justify-between">
                    <span>Seleccionar Categoría</span>
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                </div>

              <!-- Reference Price -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Precio de Referencia <span class="text-red-600">*</span>
                </label>
                <input type="text" placeholder="Ingrese el precio de referencia" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
              </div>

              <!-- Technical Description -->
              <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                  Descripción Técnica
                </label>
                <textarea rows="6" placeholder="Ingrese la descripción técnica del producto" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
              </div>

              <!-- File Upload -->
<div class="mb-6">
  <label class="block text-sm font-semibold text-gray-900 mb-2">
    Archivos del Producto (Imágenes, PDFs, etc.)
  </label>
  <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
    </svg>
    <p class="text-sm text-gray-600 font-medium">Choose Files</p>
    <p class="text-xs text-gray-500 mt-1">No file chosen</p>
  </div>
  <p class="text-xs text-gray-500 mt-2">Puedes subir múltiples imágenes o documentos relacionados con el producto.</p>
</div>

              <!-- Active Checkbox -->
              <div class="mb-6">
                <label class="flex items-center">
                  <input type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                  <span class="ml-2 text-sm text-gray-700">Activo (aparece en catálogo)</span>
                </label>
              </div>

              <!-- Submit Button -->
              <button type="submit" class="w-full btn text-white py-3 rounded-lg font-semibold transition-all hover:shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Guardar Producto
              </button>

            </form>

          </div>
        </div>

        <!-- Right Column - Preview -->
        <div class="col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Vista Previa y Sugerencias</h2>

            <!-- Preview Card -->
            <div class="mb-6">
              <p class="text-sm font-semibold text-gray-900 mb-3">Vista previa del producto</p>
              <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="aspect-square bg-gray-100 flex items-center justify-center p-6">
                  <p class="text-gray-400 text-center text-sm">La imagen principal del producto se mostrará aquí.</p>
                </div>
              </div>
            </div>

            <!-- Suggestions -->
            <div>
              <p class="text-sm font-semibold text-gray-900 mb-3">Consideraciones para Cotizaciones:</p>
              <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                  <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Asegúrate de incluir todas las especificaciones técnicas relevantes.</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Adjunta diagramas o fichas técnicas si aplica.</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Describe claramente los beneficios y usos del producto.</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Considera si este producto puede ser parte de un paquete o servicio.</span>
                </li>
              </ul>
            </div>

          </div>
        </div>

      </div>

    </div>
    </main>
</div>

<script>
    // Script simple para mostrar la vista previa de la imagen y el nombre del archivo
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = file.name;
            
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('image-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('file-name').textContent = 'Ningún archivo seleccionado';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('image-placeholder').classList.remove('hidden');
        }
    });
</script>
</body>
</html>
