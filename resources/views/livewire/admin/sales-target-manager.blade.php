<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Target Omset Sales</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola dan pantau pencapaian target sales</p>
        </div>
    </div>

    <div class="glass-card p-4 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4">
        <div class="w-full sm:w-48">
            <select wire:model.live="selectedMonth" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-32">
            <input type="number" wire:model.live.debounce.500ms="selectedYear" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($salesPersons as $sp)
            @php
                $target = $targets->get($sp->id);
                $targetAmount = $target ? $target->target_amount : 0;
                $achieved = $achievements[$sp->id] ?? 0;
                $percentage = $targetAmount > 0 ? min(100, round(($achieved / $targetAmount) * 100)) : 0;
            @endphp
            <div class="glass-card p-5 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $sp->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Area: {{ $sp->area ?: '-' }}</p>
                    </div>
                    <button wire:click="openModal({{ $sp->id }}, {{ $targetAmount }})" class="text-primary-600 hover:text-primary-700 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/40 p-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </button>
                </div>

                @if($targetAmount > 0)
                    <div class="space-y-4 mt-auto">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Pencapaian:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Rp {{ number_format($achieved, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Target:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Rp {{ number_format($targetAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium {{ $percentage >= 100 ? 'text-success-600 dark:text-success-400' : 'text-slate-600 dark:text-slate-300' }}">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $percentage >= 100 ? 'bg-success-500' : 'bg-primary-500' }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-auto pt-6 flex flex-col items-center justify-center text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Belum ada target di set.</p>
                        <button wire:click="openModal({{ $sp->id }}, 0)" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Set Target Sekarang</button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100" id="modal-title">
                                Set Target Omset
                            </h3>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Target Amount (Rp)</label>
                                <input type="number" wire:model="editTargetAmount" class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary-500">
                                @error('editTargetAmount') <span class="text-sm text-danger-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:px-6 flex flex-row-reverse">
                    <button wire:click="saveTarget" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
