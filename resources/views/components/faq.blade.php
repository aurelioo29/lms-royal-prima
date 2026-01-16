<section id="faq" class="w-full">
    <div class="mx-auto max-w-7xl px-4 lg:px-16 py-14">
        {{-- Header (full width) --}}
        <div class="max-w-3xl" data-aos="fade-up">
            <div
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-3 py-1 text-xs text-slate-700 backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-[#121293]"></span>
                Bantuan cepat
            </div>

            <h2 class="mt-4 text-3xl lg:text-4xl font-semibold tracking-tight text-slate-900">
                FAQ <span class="text-[#121293]">(Frequently Asked Question)</span>
            </h2>

            <p class="mt-3 text-sm lg:text-base text-slate-600">
                Cari jawaban cepat tentang akses pelatihan, enrollment key, mode kegiatan, dan pencatatan jam pelatihan.
            </p>
        </div>

        {{-- Accordion (full width, turun ke bawah) --}}
        <div class="mt-8 w-full" x-data="faqAccordion()" data-aos="fade-up" data-aos-delay="80">
            <div class="space-y-3">
                <template x-for="(item, i) in filtered()" :key="i">
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-transparent
                               transition-all duration-300 ease-out
                               hover:shadow-[0_12px_40px_-18px_rgba(18,18,147,0.35)]">
                        {{-- hover glow --}}
                        <div class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            style="background: radial-gradient(600px circle at 20% 10%, rgba(18,18,147,.10), transparent 55%);">
                        </div>

                        <button type="button"
                            class="relative w-full flex items-center justify-between gap-4 p-5 text-left"
                            @click="toggle(i)">
                            <div>
                                <p class="text-sm lg:text-base font-semibold text-slate-900" x-text="item.q"></p>
                                <p class="mt-1 text-xs text-slate-500" x-text="item.tag"></p>
                            </div>

                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200/70 bg-white/60 backdrop-blur
                                       text-[#121293] transition-transform duration-300"
                                :class="openIndex === i ? 'rotate-45' : ''">
                                {{-- icon: plus --}}
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                </svg>
                            </span>
                        </button>

                        <div class="relative px-5 pb-5" x-show="openIndex === i" x-collapse>
                            <div class="h-px w-full bg-slate-200/60 mb-4"></div>
                            <p class="text-sm text-slate-600 leading-relaxed" x-html="item.a"></p>
                        </div>
                    </div>
                </template>

                {{-- Empty state --}}
                <div x-show="filtered().length === 0"
                    class="rounded-3xl border border-slate-200 bg-white/60 backdrop-blur p-6 text-sm text-slate-600">
                    Tidak ada hasil untuk <span class="font-semibold" x-text="query"></span>.
                    Coba kata kunci lain.
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine data (tetap sama) --}}
    <script>
        function faqAccordion() {
            return {
                query: '',
                openIndex: 0,
                items: [{
                        q: 'Apa itu enrollment key dan kapan dibutuhkan?',
                        tag: 'Akses pelatihan',
                        a: 'Enrollment key adalah kode untuk masuk ke pelatihan tertentu. Biasanya diberikan oleh admin/penyelenggara saat pendaftaran atau saat kegiatan dibuka.'
                    },
                    {
                        q: 'Apakah pelatihan bisa online, offline, dan blended?',
                        tag: 'Mode pelatihan',
                        a: 'Bisa. Kegiatan dapat diselenggarakan <b>online</b>, <b>offline</b>, atau <b>blended</b> (kombinasi keduanya) sesuai kebijakan penyelenggara.'
                    },
                    {
                        q: 'Bagaimana jam pelatihan tercatat?',
                        tag: 'Jam pelatihan',
                        a: 'Jam pelatihan dicatat berdasarkan informasi kegiatan (durasi/jam) dan keikutsertaan peserta. Pastikan Anda mengikuti ketentuan kegiatan agar pencatatan akurat.'
                    },
                    {
                        q: 'Saya tidak menemukan kegiatan di kalender. Kenapa?',
                        tag: 'Kalender',
                        a: 'Kemungkinan kegiatan belum dipublikasikan, sudah ditutup, atau memiliki akses terbatas. Coba cek kembali di bulan yang berbeda atau hubungi admin.'
                    },
                    {
                        q: 'Apakah saya bisa melihat detail kegiatan?',
                        tag: 'Kegiatan',
                        a: 'Bisa. Klik pada event di kalender untuk melihat informasi seperti mode, lokasi, enrollment key (jika ada), jam pelatihan, dan deskripsi.'
                    },
                    {
                        q: 'Bagaimana kalau lupa akun / tidak bisa login?',
                        tag: 'Akun',
                        a: 'Silakan gunakan fitur login (atau hubungi admin) untuk bantuan reset akses. Jika fitur “lupa password” belum tersedia, admin dapat membantu aktivasi ulang.'
                    },
                ],
                toggle(i) {
                    this.openIndex = this.openIndex === i ? -1 : i;
                },
                filtered() {
                    const q = this.query.trim().toLowerCase();
                    if (!q) return this.items;
                    return this.items.filter(x => (x.q + ' ' + x.tag + ' ' + x.a).toLowerCase().includes(q));
                }
            }
        }
    </script>
</section>
