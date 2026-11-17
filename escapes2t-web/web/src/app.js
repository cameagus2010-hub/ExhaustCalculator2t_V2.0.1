(function () {
    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (items) {
            items.forEach(function (it) {
                if (it.isIntersecting) {
                    it.target.classList.add('visible');
                    io.unobserve(it.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('visible'); });
    }

    var secciones = document.querySelectorAll('section[data-seccion]');
    var enlaces = document.querySelectorAll('.menu a[data-sec]');
    if (secciones.length && 'IntersectionObserver' in window) {
        var nav = new IntersectionObserver(function (items) {
            items.forEach(function (it) {
                if (it.isIntersecting) {
                    var id = it.target.getAttribute('data-seccion');
                    enlaces.forEach(function (a) {
                        a.classList.toggle('activo', a.getAttribute('data-sec') === id);
                    });
                }
            });
        }, { rootMargin: '-45% 0px -50% 0px' });
        secciones.forEach(function (s) { nav.observe(s); });
    }
})();
