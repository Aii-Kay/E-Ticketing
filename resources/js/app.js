import './bootstrap';

import Alpine from 'alpinejs';

// Third-party JS libraries
import 'flowbite';
import AOS from 'aos';
import Swiper from 'swiper';
import Chart from 'chart.js/auto';
import flatpickr from 'flatpickr';
import Swal from 'sweetalert2';
import dayjs from 'dayjs';

// Expose ke window (opsional, memudahkan debugging di console)
window.Alpine = Alpine;
window.AOS = AOS;
window.Swiper = Swiper;
window.Chart = Chart;
window.flatpickr = flatpickr;
window.Swal = Swal;
window.dayjs = dayjs;

// Start Alpine (Breeze default)
Alpine.start();

// Inisialisasi library ketika DOM siap
document.addEventListener('DOMContentLoaded', () => {
    // AOS (animasi on-scroll)
    AOS.init();

    // Swiper slider (butuh elemen .js-example-swiper)
    const swiperEl = document.querySelector('.js-example-swiper');
    if (swiperEl) {
        new Swiper(swiperEl, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 16,
            pagination: {
                el: '.js-example-swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.js-example-swiper-next',
                prevEl: '.js-example-swiper-prev',
            },
        });
    }

    // Flatpickr datepicker (butuh input .js-flatpickr)
    const dateInput = document.querySelector('.js-flatpickr');
    if (dateInput) {
        flatpickr(dateInput, {
            dateFormat: 'Y-m-d',
        });
    }

    // Chart.js (butuh <canvas id="example-chart">)
    const chartCanvas = document.getElementById('example-chart');
    if (chartCanvas) {
        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [
                    {
                        label: 'Contoh Data',
                        data: [10, 20, 15, 30],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }

    // SweetAlert2 (butuh tombol .js-alert-demo)
    const alertButton = document.querySelector('.js-alert-demo');
    if (alertButton) {
        alertButton.addEventListener('click', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'SweetAlert2 sudah terpasang dan berjalan.',
                icon: 'success',
            });
        });
    }

    // Day.js – contoh log ke console
    console.log('Day.js sekarang:', dayjs().format('YYYY-MM-DD HH:mm:ss'));
});
