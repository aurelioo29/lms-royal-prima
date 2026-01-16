<button id="scrollToTopBtn" type="button" aria-label="Scroll to top"
    class="fixed bottom-6 right-6 z-50 hidden
           h-11 w-11 rounded-2xl
           bg-[#121293] text-white shadow-lg
           border border-white/10
           backdrop-blur
           transition-all duration-200 ease-out
           hover:-translate-y-1 hover:shadow-xl
           focus:outline-none focus-visible:ring-2 focus-visible:ring-[#121293]/40">
    {{-- icon: arrow up --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 19V5"></path>
        <path d="m5 12 7-7 7 7"></path>
    </svg>

    {{-- subtle shine --}}
    <span class="pointer-events-none absolute inset-0 rounded-2xl overflow-hidden">
        <span class="absolute -left-10 top-0 h-full w-24 rotate-12 bg-white/20 blur-sm opacity-0"
            id="scrollTopShine"></span>
    </span>
</button>
