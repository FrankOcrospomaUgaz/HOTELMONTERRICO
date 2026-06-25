<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Venta Rapida</title>

    <link href="/proyectoHotel/Cdn-Locales/pkgBootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgAwsome/css/all.css" />
    <link rel="stylesheet" href="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/css/select2.css" />
    <link rel="stylesheet" href="{{ asset('css/app2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/venta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selectCambiarHabitacion.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crearCuota.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltips.css') }}">

    <style>
        body {
            background: #f4f6f9;
            padding: 12px;
            margin: 0;
            overflow-x: hidden;
        }

        .venta-modal-shell {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <div class="venta-modal-shell">
        @include('Modulos.AgregarVenta.partials.contenido')
    </div>

    <script>
        window.__VENTA_RAPIDA_DATA__ = @json($initialData);
    </script>

    <script src="/proyectoHotel/Cdn-Locales/pkgJquery/dist/jquery.js"></script>
    <script src="/proyectoHotel/Cdn-Locales/pkgSweetAlert/dist/sweetalert2.all.js"></script>
    <script src="/proyectoHotel/Cdn-Locales/pkgSelect2/dist/js/select2.js"></script>
    <script src="/proyectoHotel/Cdn-Locales/pkgBootstrap/js/bootstrap.bundle.js"></script>

    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryIndexVenta.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryCambiarHabitacion.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyMovimiento.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyProducto.js') }}"></script>
    <script src="{{ asset('js/JqueryVentaHabitacion/JqueryDestroyServicio.js') }}"></script>
    <script src="{{ asset('js/JqueryUsuario/JqueryCreateUsuario.js') }}"></script>
    <script>
        $(document).on("click", "#cerrarVentaRapida", function () {
            if (window.parent && typeof window.parent.cerrarModalVentaRapida === "function") {
                window.parent.cerrarModalVentaRapida(true);
            }
        });
    </script>
</body>

</html>
