<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'LMS Royal Prima') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- FullCalendar CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
</head>

<body class="min-h-screen flex flex-col text-[#1b1b18] bg-gradient-to-b from-indigo-50 via-white to-white ">
    {{-- NAVBAR --}}
    <x-navbar />

    {{-- Hero --}}
    <x-hero />

    {{-- Stats Bar --}}
    <div class="mt-36">
        <x-stats-bar />
    </div>

    {{-- Gallery --}}
    <x-gallery />

    {{-- Calender Content --}}
    <x-calendar-section />

    {{-- FAQ --}}
    <x-faq />

    {{-- FOOTER --}}
    <x-footer />

    {{-- SCROLL TO TOP BUTTON --}}
    <x-scroll-to-top />
</body>

</html>
