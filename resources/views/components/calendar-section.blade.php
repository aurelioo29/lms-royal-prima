<section id="calendar-wrap" class="w-full">
    <div class="w-full py-14">
        <div class="mx-auto w-full px-4 lg:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                {{-- LEFT --}}
                <div class="lg:col-span-4" data-aos="fade-up">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200/70 bg-white/60 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-[#121293]"></span>
                        Kalender
                    </div>

                    <h2 class="mt-4 text-3xl lg:text-4xl font-semibold tracking-tight text-slate-900">
                        Jadwal kegiatan <span class="text-[#121293]">terpusat</span>
                    </h2>

                    <p class="mt-3 text-sm lg:text-base text-slate-600 max-w-md">
                        Lihat kegiatan yang sedang dibuka dan akan berlangsung.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <span
                            class="rounded-full border border-slate-200/70 bg-white/30 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                            Online
                        </span>
                        <span
                            class="rounded-full border border-slate-200/70 bg-white/30 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                            Offline
                        </span>
                        <span
                            class="rounded-full border border-slate-200/70 bg-white/30 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                            Blended
                        </span>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-[#121293] px-5 py-3 text-sm font-semibold text-white
                                   shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#0e0e70]">
                            {{-- icon: lock --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Login untuk detail
                        </a>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="lg:col-span-8" data-aos="fade-up" data-aos-delay="80">
                    <div
                        class="relative overflow-hidden rounded-[2rem] border border-slate-200/70 bg-white/55 backdrop-blur shadow-sm">
                        {{-- top accent --}}
                        <div class="h-1 w-full bg-gradient-to-r from-[#121293] via-indigo-500 to-sky-400"></div>

                        {{-- top bar --}}
                        <div class="flex items-center justify-between gap-3 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    {{-- icon: calendar --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <path d="M16 2v4"></path>
                                        <path d="M8 2v4"></path>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </span>

                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Kalender kegiatan</p>
                                    <p class="text-xs text-slate-500">Klik event untuk melihat detail</p>
                                </div>
                            </div>

                            <span
                                class="inline-flex items-center rounded-full border border-slate-200/70 bg-white/60 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                                Live
                            </span>
                        </div>

                        <div class="px-4 sm:px-5 pb-5">
                            <div id="calendar" class="w-full min-h-[520px]"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- FullCalendar styling --}}
    <style>
        #calendar .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 10px;
        }

        #calendar .fc .fc-toolbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
        }

        #calendar .fc .fc-button {
            border: 1px solid rgba(148, 163, 184, .6);
            background: rgba(255, 255, 255, .65);
            backdrop-filter: blur(8px);
            color: #0f172a;
            border-radius: 14px;
            padding: .45rem .7rem;
            font-weight: 600;
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
        }

        #calendar .fc .fc-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -18px rgba(15, 23, 42, .55);
            background: rgba(255, 255, 255, .9);
        }

        #calendar .fc .fc-button:focus {
            box-shadow: 0 0 0 4px rgba(18, 18, 147, .15);
        }

        #calendar .fc .fc-button-primary:not(:disabled).fc-button-active,
        #calendar .fc .fc-button-primary:not(:disabled):active {
            background: rgba(18, 18, 147, .10);
            border-color: rgba(18, 18, 147, .25);
            color: #121293;
        }

        #calendar .fc .fc-day-today {
            background: rgba(18, 18, 147, .06) !important;
        }

        #calendar .fc .fc-daygrid-event {
            border-radius: 10px;
            padding: 0 6px;
            border: 1px solid rgba(18, 18, 147, .18);
            background: rgba(18, 18, 147, .08);
            color: #0f172a;
        }

        #calendar .fc .fc-event-title {
            font-weight: 600;
        }

        @media (max-width: 640px) {
            #calendar .fc .fc-toolbar-title {
                font-size: 1rem;
                line-height: 1.2;
            }

            #calendar .fc .fc-button {
                padding: .25rem .5rem;
                font-size: .75rem;
                line-height: 1rem;
                border-radius: 12px;
            }

            #calendar .fc .fc-col-header-cell-cushion {
                font-size: .70rem;
                padding: 4px 2px;
            }

            #calendar .fc .fc-daygrid-day-number {
                font-size: .70rem;
                padding: 2px 4px;
            }

            #calendar .fc .fc-daygrid-day-frame {
                min-height: 56px;
            }

            #calendar .fc .fc-daygrid-event {
                margin: 1px 2px;
            }

            #calendar .fc .fc-event-title {
                font-size: .70rem;
            }
        }
    </style>

    {{-- calendar init --}}
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
                    method: 'GET'
                },

                eventClick: function(info) {
                    const p = info.event.extendedProps || {};
                    const isGuest = !p.enrollment_key;

                    if (typeof Swal === 'undefined') {
                        alert(info.event.title);
                        return;
                    }

                    if (isGuest) {
                        Swal.fire({
                            title: info.event.title,
                            html: `
                              <div style="text-align:left; line-height:1.7">
                                <div><b>Mode:</b> ${p.mode ?? '-'}</div>
                                <div><b>Lokasi:</b> ${p.location ?? '-'}</div>
                                <div><b>Jam:</b> ${p.training_hours ?? '-'}</div>
                                <div style="margin-top:10px; color:#6b7280; font-size:13px">
                                  Login untuk melihat detail lengkap.
                                </div>
                              </div>
                            `,
                            icon: "info",
                            showCancelButton: true,
                            confirmButtonText: "Login",
                            cancelButtonText: "Tutup",
                            confirmButtonColor: "#121293",
                            focusConfirm: false,
                        }).then((result) => {
                            if (result.isConfirmed) window.location.href =
                                "{{ route('login') }}";
                        });
                        return;
                    }

                    Swal.fire({
                        title: info.event.title,
                        html: `
                          <div style="text-align:left; line-height:1.7">
                            <div><b>Enrollment Key:</b> ${p.enrollment_key ?? '-'}</div>
                            <div><b>Mode:</b> ${p.mode ?? '-'}</div>
                            <div><b>Lokasi:</b> ${p.location ?? '-'}</div>
                            <div><b>Jam:</b> ${p.training_hours ?? '-'}</div>
                            <div style="margin-top:10px; color:#6b7280; font-size:13px">
                              ${p.description ?? ''}
                            </div>
                          </div>
                        `,
                        icon: "success",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#121293",
                        focusConfirm: false,
                    });
                },
            });

            calendar.render();
            window.addEventListener('resize', () => calendar.updateSize());
            const ro = new ResizeObserver(() => calendar.updateSize());
            ro.observe(el);
        });
    </script>
</section>
