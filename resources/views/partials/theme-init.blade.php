{{-- 
    Anti-FOUC dark mode initializer.
    Runs synchronously before CSS/JS loads, so the correct theme class is
    applied on the very first paint of every full page load (no flash).
    Must be included as the FIRST thing inside <head>, before @vite.
    This is intentionally plain JS (no Alpine/Livewire dependency) because
    Alpine hasn't initialized yet at this point in the page load.
--}}
<script>
    (function () {
        try {
            var isDark = localStorage.getItem('darkMode') === 'true';
            document.documentElement.classList.toggle('dark', isDark);
        } catch (e) {
            // localStorage unavailable (privacy mode, etc.) — default to light.
        }
    })();
</script>
