import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import Alpine from "alpinejs";
import Typed from "typed.js";
import AOS from "aos";
import "aos/dist/aos.css";
import collapse from "@alpinejs/collapse";
Alpine.plugin(collapse);
import Swiper from "swiper";
import { Autoplay, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import Swal from "sweetalert2";
window.Swal = Swal;

window.Alpine = Alpine;
Alpine.start();

function initTimePickers() {
    document.querySelectorAll("[data-timepicker]").forEach((el) => {
        if (el._flatpickr) return;

        flatpickr(el, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: true,
            minuteIncrement: 5,
            allowInput: true,
        });
    });
}

function initTypedHero() {
    const el = document.querySelector("#typed-hero");
    if (!el) return;

    if (el.dataset.typedInit === "1") return;
    el.dataset.typedInit = "1";

    new Typed("#typed-hero", {
        strings: [
            "Progress Yang Kebaca.",
            "Pelatihan Jadi Terarah.",
            "Jam Pelatihan Tercatat.",
            "Info Kegiatan Selalu Update.",
            "Semua Rapi, Semua Jelas.",
        ],
        typeSpeed: 70,
        backSpeed: 40,
        backDelay: 1300,
        startDelay: 200,
        loop: true,
        smartBackspace: true,
        showCursor: true,
        cursorChar: "▍",
    });
}

function initScrollToTop() {
    const btn = document.getElementById("scrollToTopBtn");
    if (!btn) return;

    const shine = document.getElementById("scrollTopShine");

    const toggle = () => {
        if (window.scrollY > 350) {
            btn.classList.remove("hidden");
            btn.classList.add("block");
        } else {
            btn.classList.add("hidden");
            btn.classList.remove("block");
        }
    };

    toggle();
    window.addEventListener("scroll", toggle, { passive: true });

    btn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // tiny shine on hover (optional)
    if (shine) {
        btn.addEventListener("mouseenter", () => {
            shine.style.opacity = "1";
            shine.style.animation = "scrollShine 700ms ease-out";
        });
        btn.addEventListener("mouseleave", () => {
            shine.style.opacity = "0";
            shine.style.animation = "none";
        });
    }
}

function initAOS() {
    AOS.init({
        duration: 450, // lebih cepat, ga “berat”
        easing: "ease-out",
        once: true,
        offset: 40, // lebih kecil biar ga telat muncul
        delay: 0,
        anchorPlacement: "top-bottom",
    });

    // Refresh beberapa kali karena layout bisa berubah setelah load
    requestAnimationFrame(() => AOS.refresh());
    setTimeout(() => AOS.refresh(), 150);
    setTimeout(() => AOS.refreshHard(), 600);
}

// kalau halaman berubah (Livewire navigate), refresh AOS
document.addEventListener("DOMContentLoaded", initAOS);
document.addEventListener("livewire:navigated", () => {
    initAOS();
    AOS.refreshHard();
});

document.addEventListener("DOMContentLoaded", initScrollToTop);
document.addEventListener("livewire:navigated", initScrollToTop);

document.addEventListener("DOMContentLoaded", () => {
    initTimePickers();
    initTypedHero();
});

document.addEventListener("livewire:navigated", () => {
    initTimePickers();
    initTypedHero();
});

function initCountUp() {
    const els = document.querySelectorAll(".countup");
    if (!els.length) return;

    const animate = (el) => {
        if (el.dataset.done === "1") return;
        el.dataset.done = "1";

        const to = Number(el.dataset.to || 0);
        const suffix = el.dataset.suffix || "";
        const duration = 900;
        const start = performance.now();
        const from = 0;

        const step = (now) => {
            const p = Math.min(1, (now - start) / duration);
            const val = Math.round(
                from + (to - from) * (1 - Math.pow(1 - p, 3))
            );
            el.textContent = `${val}${suffix}`;
            if (p < 1) requestAnimationFrame(step);
        };

        requestAnimationFrame(step);
    };

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) animate(e.target);
            });
        },
        { threshold: 0.35 }
    );

    els.forEach((el) => io.observe(el));
}

document.addEventListener("DOMContentLoaded", initCountUp);
document.addEventListener("livewire:navigated", initCountUp);

function initGallerySwiper() {
    const el = document.querySelector(".gallery-swiper");
    if (!el) return;

    // prevent double-init
    if (el.dataset.swiperInit === "1") return;
    el.dataset.swiperInit = "1";

    new Swiper(el, {
        modules: [Autoplay, Navigation, Pagination],
        loop: true,
        speed: 700,
        spaceBetween: 18,
        centeredSlides: false,

        autoplay: {
            delay: 2600,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },

        pagination: {
            el: ".gallery-pagination",
            clickable: true,
        },

        navigation: {
            nextEl: ".gallery-next",
            prevEl: ".gallery-prev",
        },

        breakpoints: {
            0: { slidesPerView: 1.1 },
            640: { slidesPerView: 2.1 },
            1024: { slidesPerView: 3.2 },
            1280: { slidesPerView: 4.2 },
        },
    });
}

document.addEventListener("DOMContentLoaded", initGallerySwiper);
document.addEventListener("livewire:navigated", initGallerySwiper);
