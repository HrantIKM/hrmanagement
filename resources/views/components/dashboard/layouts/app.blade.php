<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">

    {{-- Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Styles  --}}
    <link href="{{ asset('/css/dashboard/datatable.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/dashboard/dashboard-app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    {{ $head ?? '' }}

    {{-- Data for Js files  --}}
    <x-dashboard.layouts.partials.data-for-js/>
</head>
<body>

<main class="d-flex page">
    <x-dashboard.layouts.partials.sidebar />

    <div class="wrapper w-100 d-flex flex-column">
        <x-dashboard.layouts.partials.header />
        <div class="content d-flex flex-column flex-column-fluid">
            <x-dashboard.layouts.partials.subheader />
            {{ $slot ?? '' }}
        </div>
    </div>
</main>

<x-dashboard.partials.modals />

{{-- Core Js  --}}
<script src="{{ mix('/js/dashboard/dashboard-app.js') }}"></script>

<script src="{{ mix('/js/dashboard/bundle.js') }}"></script>

{{ $scripts ?? '' }}

</body>
</html>
