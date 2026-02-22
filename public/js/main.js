document.addEventListener('DOMContentLoaded', () => {
    console.log('FrameworkMVC: Frontend cargado correctamente.');

    const btn = document.querySelector('.btn');
    if (btn) {
        btn.addEventListener('click', (e) => {
            if (btn.getAttribute('href') === '#') {
                e.preventDefault();
                alert('¡JavaScript esta funcionando desde public/Js/main.js!');
            }
        });
    }
});
