<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('vuexy') }}/"
    data-template="vertical-menu-template" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Login') | FOSA Task Management</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('vuexy/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/fonts/flag-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vuexy/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/libs/@form-validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('vuexy/vendor/css/pages/page-auth.css') }}" />

    <script src="{{ asset('vuexy/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('vuexy/js/config.js') }}"></script>
</head>

<body>
    @yield('content')

    <script src="{{ asset('vuexy/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('vuexy/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    <script src="{{ asset('vuexy/js/main.js') }}"></script>
    <script src="{{ asset('vuexy/js/pages-auth.js') }}"></script>
</body>
</html>
