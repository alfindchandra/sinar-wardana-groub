<div 
    x-data="{ 
        toasts: [],
        add(toast) {
            toast.id = Date.now();
            this.toasts.push(toast);
            setTimeout(() => { this.remove(toast.id) }, toast.duration || 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="add($event.detail[0] || $event.detail)"
    class="fixed top-4 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto p-4 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 border backdrop-blur-md relative overflow-hidden"
            :class="{
                'bg-success-50/90 border-success-200 dark:bg-success-900/40 dark:border-success-800 text-success-800 dark:text-success-300': toast.type === 'success',
                'bg-danger-50/90 border-danger-200 dark:bg-danger-900/40 dark:border-danger-800 text-danger-800 dark:text-danger-300': toast.type === 'error',
                'bg-warning-50/90 border-warning-200 dark:bg-warning-900/40 dark:border-warning-800 text-warning-800 dark:text-warning-300': toast.type === 'warning',
                'bg-primary-50/90 border-primary-200 dark:bg-primary-900/40 dark:border-primary-800 text-primary-800 dark:text-primary-300': toast.type === 'info',
            }"
        >
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <!-- Icons based on type -->
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </template>
                </div>
                <div class="flex-1 w-0">
                    <p class="font-medium text-sm" x-text="toast.title"></p>
                    <p class="text-sm opacity-90 mt-1" x-show="toast.message" x-text="toast.message"></p>
                </div>
                <div class="flex-shrink-0 flex">
                    <button @click="remove(toast.id)" class="inline-flex text-current opacity-70 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <!-- Progress bar -->
            <div class="absolute bottom-0 left-0 h-1 w-full bg-current opacity-20">
                <div class="h-full bg-current opacity-50" :style="`animation: shrink ${toast.duration || 4000}ms linear forwards;`"></div>
            </div>
        </div>
    </template>
    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</div>
