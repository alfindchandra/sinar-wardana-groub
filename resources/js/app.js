import './bootstrap';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';

window.Chart = Chart;
window.Swal = Swal;

/**
 * Helper global untuk memicu <x-toast /> dari mana saja.
 * Contoh: toast('success', 'Data berhasil disimpan', 'Berhasil')
 * Livewire component juga bisa memicu ini lewat: $this->dispatch('toast', type: 'success', message: '...')
 */
window.toast = function (type = 'info', message = '', title = null, duration = 4000) {
    window.dispatchEvent(new CustomEvent('toast', {
        detail: { type, message, title, duration },
    }));
};

    document.addEventListener('alpine:init', () => {
        Alpine.store('theme', {
            dark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            
            toggle() {
                this.dark = !this.dark;
                localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                if (this.dark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    });
