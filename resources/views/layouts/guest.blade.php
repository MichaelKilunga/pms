<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js'], 'build') --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Styles -->
    @livewireStyles
</head>


<body>
    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        {{ $slot }}
    </div>

    @livewireScripts
    <script>
        $(document).ready(function() {
            $('button').on('click', function(event) {
                const $this = $(this);

                if ($this.is('button[type="submit"]')) {
                    const $form = $this.closest('form');
                    if ($form.length) {
                        if ($form[0].checkValidity()) {

                            $this.css('pointer-events', 'none')
                                .text(
                                    'Loading...'
                                );

                        }
                    }
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>
</body>

</html>
