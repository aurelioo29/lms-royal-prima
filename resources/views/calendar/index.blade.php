<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h1 class="text-lg font-semibold text-slate-900">Training Calendar</h1>
                            <p class="text-sm text-slate-600">Only published courses appear here.</p>
                        </div>
                    </div>

                    <div class="mt-5">
                        {{-- NO horizontal scroll --}}
                        <div id="calendar" class="w-full min-h-[620px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FullCalendar CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <style>
        /* Toolbar wrap (biar rapi di layar kecil) */
        .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ============ MOBILE COMPACT MODE ============ */
        @media (max-width: 640px) {

            /* Judul bulan lebih kecil */
            .fc .fc-toolbar-title {
                font-size: 1rem;
                line-height: 1.2;
            }

            /* Tombol prev/next/today lebih kecil */
            .fc .fc-button {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.75rem !important;
                line-height: 1rem !important;
            }

            /* Nama hari lebih kecil */
            .fc .fc-col-header-cell-cushion {
                font-size: 0.70rem;
                padding: 4px 2px;
            }

            /* Angka tanggal lebih kecil + rapat */
            .fc .fc-daygrid-day-number {
                font-size: 0.70rem;
                padding: 2px 4px;
            }

            /* Tinggi row lebih pendek (biar "gepeng") */
            .fc .fc-daygrid-day-frame {
                min-height: 56px;
                /* adjust kalau mau lebih gepeng */
            }

            /* Event bar: kecil + ellipsis */
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

            /* Kurangi padding cell biar muat */
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
                    right: '' // month only
                },

                // this helps month view fit better (less stretching)
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

            // Recalculate on resize + sidebar collapse changes
            window.addEventListener('resize', () => calendar.updateSize());

            const ro = new ResizeObserver(() => calendar.updateSize());
            ro.observe(el);
        });
    </script>
</x-app-layout>
