<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link href="{{ asset(mix('css/client/app.css')) }}" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset(mix('js/client/app.js')) }}" defer></script>
    </head>
    <body>
        @include('includes.header')
        <div class="view">
            <main class="view__body">
                <div>
                    {{ $breadcrumb }}
                </div>    
                <div class="content">
                    {{ $slot }}
                </div>
            </main>
            @include('includes.side-view')
        </div>
        @include('includes.footer')
    </body>
</html>
