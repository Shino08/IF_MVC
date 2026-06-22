<!-- END MAIN CONTENT -->
<footer class="bg-gray-100 py-12 border-t border-gray-200 mt-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 text-center md:text-left">
            <div class="col-span-1 md:col-span-2">
                <img src="<?= $base_url ?? '' ?>/img/Photoroom-20251106_165742.png" alt="InstalFuego Logo" class="h-10 object-contain mb-4 mx-auto md:mx-0">
                <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto md:mx-0">
                    Especialistas en sistemas de seguridad contra incendios. Protegemos lo que más valoras con tecnología certificada.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-900 mb-4">Productos</h4>
                <ul class="space-y-2 text-sm text-gray-500 font-medium">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Extintores</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Detectores de Humo</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Sistemas de Riego</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-wider text-gray-900 mb-4">Empresa</h4>
                <ul class="space-y-2 text-sm text-gray-500 font-medium">
                    <li><a href="#" class="hover:text-red-600 transition-colors">Acerca de</a></li>
                    <li><a href="#" class="hover:text-red-600 transition-colors">Servicios</a></li>
                    <li><a href="<?= $base_url ?? '' ?>/login" class="hover:text-red-600 transition-colors">Acceder</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-300 mt-12 pt-8 flex flex-col md:flex-row justify-center items-center text-sm text-gray-600 font-medium">
            <p>© 2026 InstalFuego. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<script>
    const mobileBtn = document.getElementById('mobile-menu-btn');
    if (mobileBtn) {
        mobileBtn.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                if (menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                }
            }
        });
    }
</script>

<script src="<?= $base_url ?? '' ?>/js/main.js"></script>
</body>
</html>
