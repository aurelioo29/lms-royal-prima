<section id="gallery" class="w-full">
    {{-- full width background --}}
    <div class="w-full py-14">
        <div class="mx-auto w-full px-4 lg:px-16">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-3 py-1 text-xs text-slate-700 backdrop-blur"
                        data-aos="fade-up">
                        <span class="h-2 w-2 rounded-full bg-[#121293]"></span>
                        Dokumentasi kegiatan
                    </div>

                    <h2 class="mt-4 text-3xl lg:text-4xl font-semibold tracking-tight text-slate-900" data-aos="fade-up"
                        data-aos-delay="80">
                        Kegiatan yang <span class="text-[#121293]">terlihat nyata</span>
                    </h2>

                    <p class="mt-3 text-sm lg:text-base text-slate-600 max-w-2xl" data-aos="fade-up"
                        data-aos-delay="140">
                        Foto-foto pelatihan, workshop, dan sesi kompetensi yang sedang berjalan.
                    </p>
                </div>

                {{-- nav buttons --}}
                <div class="flex items-center gap-2" data-aos="fade-up" data-aos-delay="200">
                    <button type="button"
                        class="gallery-prev inline-flex h-11 w-11 items-center justify-center rounded-2xl
                               border border-slate-200 bg-white/70 backdrop-blur text-slate-800
                               transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6"></path>
                        </svg>
                    </button>

                    <button type="button"
                        class="gallery-next inline-flex h-11 w-11 items-center justify-center rounded-2xl
                               bg-[#121293] text-white border border-[#121293]
                               transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:bg-[#0e0e70]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- swiper --}}
            <div class="mt-8">
                <div class="swiper gallery-swiper" data-aos="fade-up" data-aos-delay="260">
                    <div class="swiper-wrapper">
                        @php
                            // ganti/extend list ini sesuai foto kamu
                            $images = [
                                'images/gallery/1.jpg',
                                'images/gallery/2.jpeg',
                                'images/gallery/3.jpeg',
                                'images/gallery/4.jpeg',
                                'images/gallery/5.webp',
                            ];
                        @endphp

                        @foreach ($images as $img)
                            <div class="swiper-slide">
                                <div
                                    class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-transparent">
                                    {{-- image --}}
                                    <div class="relative aspect-[4/3]">
                                        <img src="{{ asset($img) }}" alt="Kegiatan RSU Royal Prima"
                                            class="absolute inset-0 h-full w-full object-cover
                                                    transition-transform duration-700 ease-out group-hover:scale-[1.05] group-hover:cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- pagination --}}
                <div class="gallery-pagination mt-5 flex items-center justify-center"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes galleryShine {
            0% {
                transform: translateX(-60%) rotate(12deg);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            100% {
                transform: translateX(240%) rotate(12deg);
                opacity: 0;
            }
        }

        /* make pagination dots nicer without hardcoding colors too much */
        .gallery-pagination .swiper-pagination-bullet {
            opacity: .35;
        }

        .gallery-pagination .swiper-pagination-bullet-active {
            opacity: 1;
        }
    </style>
</section>
