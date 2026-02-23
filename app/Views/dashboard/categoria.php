<?php $title = 'Gestión de Categorías'; $active_nav = 'categorias'; ?>
<?php require_once __DIR__ . '/../layouts/_head.php'; ?>
<body class="bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../layouts/_sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">

        <header class="bg-white border-b border-gray-200 px-8 py-5 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Categorías</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Organiza las clasificaciones del catálogo</p>
                </div>
            </div>
        </header>

        <div class="p-8">

            <div id="ajax-alert" class="hidden px-4 py-3 rounded-xl text-sm mb-6 flex items-center space-x-2 border">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span id="ajax-alert-text"></span>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Nueva Categoría</h2>

                        <form id="form-categoria" action="<?= $base_url ?? '' ?>/dashboard/categorias/store" method="POST">
                            
                            <input type="hidden" id="categoria_id" name="id" value="">

                            <div class="mb-6">
                                <label for="nombre_categoria" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nombre de la Categoría <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="nombre_categoria" name="nombre" placeholder="Ej: Válvulas de bronce" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                            </div>

                            <button type="submit" id="btn-save-cat" class="w-full bg-red-700 text-white py-3 rounded-xl font-bold transition-all hover:bg-red-800 shadow-md flex items-center justify-center">
                                <span id="btn-text-cat">Guardar Categoría</span>
                                <svg id="btn-spinner-cat" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>

                            <button type="button" id="btn-cancel-edit" class="hidden w-full mt-3 bg-white border border-gray-300 text-gray-700 py-3 rounded-xl font-bold transition-all hover:bg-gray-50 flex items-center justify-center">
                                Cancelar Edición
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h2 class="text-xl font-bold text-gray-900">Listado Activo</h2>
                            <div class="relative">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" id="buscador-categorias" placeholder="Buscar categoría..." class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="tabla-categorias">
                                <thead>
                                    <tr class="bg-white border-b border-gray-200">
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-16 text-center">ID</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre de Categoría</th>
                                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    
                                    <?php if (empty($categorias)): ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                                No hay categorías registradas aún.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($categorias as $cat): ?>
                                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-center">
                                                    #<?= htmlspecialchars((string)$cat['id']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-bold text-gray-900 cat-nombre-td">
                                                        <?= htmlspecialchars($cat['nombre']) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        
                                                        <button type="button" class="btn-editar-cat p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                                                title="Editar" 
                                                                data-id="<?= $cat['id'] ?>" 
                                                                data-nombre="<?= htmlspecialchars($cat['nombre']) ?>">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                            </svg>
                                                        </button>

                                                        <button type="button" class="btn-eliminar-cat p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                                title="Eliminar" 
                                                                data-id="<?= $cat['id'] ?>">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<div id="custom-modal" 
     class="hidden transition-opacity" 
     style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 99999; background-color: rgba(17, 24, 39, 0.7); display: none; align-items: center; justify-content: center;" 
     aria-modal="true" role="dialog">
     
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 transform transition-all scale-100 relative">
        
        <div class="flex items-center space-x-4 mb-4">
            <div id="modal-icon-bg" class="flex-shrink-0 w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 id="modal-title" class="text-xl font-black text-gray-900 leading-tight">Eliminar Categoría</h3>
            </div>
        </div>

        <div class="mb-6">
            <p id="modal-message" class="text-sm text-gray-600 leading-relaxed">
                ¿Estás seguro de que deseas eliminar esta categoría? Si tiene productos asignados, el sistema no te permitirá borrarla.
            </p>
        </div>

        <div class="flex justify-end space-x-3 pt-2">
            <button type="button" id="btn-modal-cancel" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                Cancelar
            </button>
            <button type="button" id="btn-modal-confirm" class="px-5 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-md flex items-center">
                <span id="modal-btn-text">Sí, eliminar</span>
            </button>
        </div>
        
    </div>
</div>

<script>
    const BASE_URL = "<?= $base_url ?? '' ?>";
</script>
<script src="<?= $base_url ?? '' ?>/js/gestionCategorias.js"></script>

</body>
</html>