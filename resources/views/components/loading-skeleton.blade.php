@props(['type' => 'card'])

<div {{ $attributes->merge(['class' => 'animate-pulse']) }}>
    @if($type === 'chart')
        <div class="h-64 bg-slate-200 dark:bg-slate-700 rounded-xl w-full"></div>
    @elseif($type === 'table')
        <div class="space-y-4">
            <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
            <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
            <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
            <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
            <div class="h-10 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
        </div>
    @elseif($type === 'card')
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-lg"></div>
                <div class="space-y-2 flex-1">
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-1/3"></div>
                    <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/2"></div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="h-8 bg-slate-200 dark:bg-slate-700 rounded w-full"></div>
                <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-2/3"></div>
            </div>
        </div>
    @else
        <div class="h-full w-full bg-slate-200 dark:bg-slate-700 rounded-lg"></div>
    @endif
</div>
