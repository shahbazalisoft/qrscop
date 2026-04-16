<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$store->name}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body>
    @yield('content')

    <!-- Cart Section (Common for all templates) -->
    {{-- @include('menu-templates.partials.cart') --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script> --}}

    <!-- Template Specific JS -->
    @yield('scripts')
</body>
</html>
