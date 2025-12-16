import "./bootstrap";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

function initTimePickers() {
    document.querySelectorAll("[data-timepicker]").forEach((el) => {
        // cegah double-init (kalau ke-render ulang)
        if (el._flatpickr) return;

        flatpickr(el, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: true, // <<< penting biar ga pake UI native yang suka jadul/AMPM
            minuteIncrement: 5, // optional biar enak
            allowInput: true, // user bisa ketik HH:MM
        });
    });
}

document.addEventListener("DOMContentLoaded", initTimePickers);
document.addEventListener("livewire:navigated", initTimePickers); // kalau suatu saat pakai livewire
