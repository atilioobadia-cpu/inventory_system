@props(['type' => 'success', 'message' => '', 'title' => ''])

@php
$styles = [
    'success' => ['bg' => 'bg-success-light', 'border' => 'border-success', 'text' => 'text-success', 'iconColor' => 'text-success'],
    'error' => ['bg' => 'bg-danger-light', 'border' => 'border-danger', 'text' => 'text-danger', 'iconColor' => 'text-danger'],
    'warning' => ['bg' => 'bg-warning-light', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'iconColor' => 'text-amber-500'],
    'info' => ['bg' => 'bg-gray-100', 'border' => 'border-primary', 'text' => 'text-primary', 'iconColor' => 'text-primary'],
];
$style = $styles[$type] ?? $styles['success'];

$messages = [
    'success' => session('success'),
    'error' => session('error'),
    'warning' => session('warning'),
    'info' => session('info'),
];
@endphp

<div x-data="{
    alerts: [
        @if(session('success')) { type: 'success', message: '{{ session('success') }}' } @endif
        @if(session('error')) { type: 'error', message: '{{ session('error') }}' } @endif
        @if(session('warning')) { type: 'warning', message: '{{ session('warning') }}' } @endif
        @if(session('info')) { type: 'info', message: '{{ session('info') }}' } @endif
    ],
    dismiss(index) {
        this.alerts.splice(index, 1);
    },
    init() {
        setTimeout(() => {
            this.alerts.forEach((_, i) => {
                setTimeout(() => this.dismiss(i), 5000);
            });
        }, 100);
    }
}" x-cloak>
    <template x-for="(alert, index) in alerts" :key="index">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-4 rounded-lg border px-4 py-3 flex items-start gap-3"
             :class="{
                 'bg-success-light border-success': alert.type === 'success',
                 'bg-danger-light border-danger': alert.type === 'error',
                 'bg-warning-light border-amber-200': alert.type === 'warning',
                 'bg-gray-100 border-primary': alert.type === 'info'
             }">

            {{-- Icon --}}
            <div class="flex-shrink-0 mt-0.5">
                {{-- Success --}}
                <template x-if="alert.type === 'success'">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </template>
                {{-- Error --}}
                <template x-if="alert.type === 'error'">
                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                </template>
                {{-- Warning --}}
                <template x-if="alert.type === 'warning'">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                    </svg>
                </template>
                {{-- Info --}}
                <template x-if="alert.type === 'info'">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                    </svg>
                </template>
            </div>

            {{-- Message --}}
            <div class="flex-1">
                <p class="text-sm font-medium" x-text="alert.message"
                   :class="{
                       'text-success': alert.type === 'success',
                       'text-danger': alert.type === 'error',
                       'text-amber-800': alert.type === 'warning',
                       'text-primary': alert.type === 'info'
                   }"></p>
            </div>

            {{-- Close Button --}}
            <button @click="dismiss(index)" class="flex-shrink-0"
                    :class="{
                        'text-success hover:text-success': alert.type === 'success',
                        'text-danger hover:text-danger': alert.type === 'error',
                        'text-amber-500 hover:text-warning': alert.type === 'warning',
                        'text-primary hover:text-primary': alert.type === 'info'
                    }">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>