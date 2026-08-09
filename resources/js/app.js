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
