<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- FullCalendar CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="w-full px-4 lg:px-16 py-5 text-sm">
        @if (Route::has('login'))
            <nav class="flex items-center justify-between w-full">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-royal.png') }}" class="h-12 w-auto" alt="Royal LMS">
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium border border-[#121293] text-[#121293] bg-white hover:bg-[#121293] hover:text-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium border border-[#121293] text-[#121293] bg-transparent hover:bg-[#121293] hover:text-white transition-colors">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium bg-[#121293] text-white border border-[#121293] hover:bg-[#0e0e70] transition-colors">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        @endif
    </header>

    {{-- MAIN CONTENT --}}
    <main class="w-full flex-1 flex flex-col justify-center">
        <div class="mx-auto px-4 lg:px-16 space-y-6">
            {{-- Title + subtitle --}}
            <div class="mt-2">
                <h1 class="text-3xl font-semibold text-slate-900">Calendar</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar tanggal setiap kegiatan yang sedang berlangsung dan sedang dibuka.
                </p>
            </div>

            {{-- Calendar full width --}}
            <section class="mt-2">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden w-full">
                    <div class="h-1 w-full bg-[#121293]"></div>

                    <div class="p-4 sm:p-5">
                        <div id="calendar" class="w-full min-h-[520px]"></div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="w-full border-t border-slate-200 bg-[#121293]">
        <div class="max-w-6xl mx-auto px-4 lg:px-0 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs sm:text-sm text-white">
                &copy; {{ now()->year }} RSU Royal Prima — Learning Management System.
            </p>
            <p class="text-xs sm:text-sm text-white">
                Dikembangkan untuk peningkatan kompetensi tenaga kesehatan.
            </p>
        </div>
    </footer>

    {{-- FullCalendar styling override (mobile) --}}
    <style>
        .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (max-width: 640px) {
            .fc .fc-toolbar-title {
                font-size: 1rem;
                line-height: 1.2;
            }

            .fc .fc-button {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.75rem !important;
                line-height: 1rem !important;
            }

            .fc .fc-col-header-cell-cushion {
                font-size: 0.70rem;
                padding: 4px 2px;
            }

            .fc .fc-daygrid-day-number {
                font-size: 0.70rem;
                padding: 2px 4px;
            }

            .fc .fc-daygrid-day-frame {
                min-height: 56px;
            }

            .fc .fc-daygrid-event {
                margin: 1px 2px;
                padding: 0 4px;
                border-radius: 6px;
            }

            .fc .fc-event-title,
            .fc .fc-event-time {
                font-size: 0.70rem;
                line-height: 1rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .fc .fc-daygrid-day-top {
                justify-content: flex-end;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('calendar');
            if (!el) return;

            const calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                height: 'auto',
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                expandRows: true,
                fixedWeekCount: false,
                displayEventTime: false,
                events: {
                    url: "{{ route('calendar.events') }}",
                    method: 'GET',
                },
                eventClick: function(info) {
                    const p = info.event.extendedProps || {};
                    alert(
                        `${info.event.title}\n\n` +
                        `Key: ${p.enrollment_key ?? '-'}\n` +
                        `Mode: ${p.mode ?? '-'}\n` +
                        `Location: ${p.location ?? '-'}\n` +
                        `Hours: ${p.training_hours ?? '-'}\n\n` +
                        `${p.description ?? ''}`
                    );
                },
            });

            calendar.render();
            window.addEventListener('resize', () => calendar.updateSize());
            const ro = new ResizeObserver(() => calendar.updateSize());
            ro.observe(el);
        });
    </script>
</body>

</html>
