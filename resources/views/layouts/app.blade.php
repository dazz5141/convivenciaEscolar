<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Convivencia Escolar')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    @include('partials.sidebar')

    <div class="main-content">
        @include('partials.navbar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        @include('partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
    @yield('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // restaurar submenus abiertos usando localStorage
            document.querySelectorAll(".submenu").forEach(sub => {
                const key = sub.dataset.key;
                if (localStorage.getItem("submenu_" + key) === "open") {
                    sub.classList.add("open");
                }
            });

            // activar toggles
            document.querySelectorAll(".submenu-toggle").forEach(toggle => {
                toggle.addEventListener("click", function (e) {
                    e.preventDefault();

                    const parent = this.closest(".submenu");
                    const key = parent.dataset.key;

                    // cerrar otros
                    document.querySelectorAll(".submenu").forEach(s => {
                        if (s !== parent) {
                            s.classList.remove("open");
                            localStorage.removeItem("submenu_" + s.dataset.key);
                        }
                    });

                    // abrir/cerrar este
                    parent.classList.toggle("open");

                    // guardar estado
                    if (parent.classList.contains("open")) {
                        localStorage.setItem("submenu_" + key, "open");
                    } else {
                        localStorage.removeItem("submenu_" + key);
                    }
                });
            });

        });
        </script>
</body>
</html>
