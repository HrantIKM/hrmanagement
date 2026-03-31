<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">

    {{-- Core Js  --}}
    @vite(['resources/js/dashboard/dashboard-app.js'])
    @vite(['resources/js/bundle.js'])

    {{-- Moment --}}
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    {{-- Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Styles  --}}
    <link href="{{ asset('/css/dashboard/datatable.css') }}" rel="stylesheet">
    @vite('resources/sass/dashboard/dashboard-app.scss')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    {{ $head ?? '' }}

    {{-- Data for Js files  --}}
    <x-dashboard.layouts.partials.data-for-js/>
</head>
<body>

<main class="d-flex page">
    <x-dashboard.layouts.partials.sidebar/>

    <div class="wrapper w-100 d-flex flex-column">
        <x-dashboard.layouts.partials.header/>
        <div class="content d-flex flex-column flex-column-fluid">
            <x-dashboard.layouts.partials.subheader/>
            {{ $slot ?? '' }}
        </div>
    </div>
</main>

<x-dashboard.partials.modals/>

{{ $scripts ?? '' }}

</body>
</html>
