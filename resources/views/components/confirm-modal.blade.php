@props([
    'name' => 'confirmModal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmUrl' => '#',
    'confirmText' => 'Confirm',
    'color' => 'danger',
    'method' => 'POST',
])

@php
$colorClasses = [
    'danger' => 'bg-danger hover:bg-danger',
    'warning' => 'bg-warning hover:bg-amber-600',
    'success' => 'bg-success hover:bg-success-hover',
    'accent' => 'bg-accent hover:bg-accent',
];
$buttonClass = $colorClasses[$color] ?? $colorClasses['danger'];
@endphp

<div x-data="{ {{ $name }}: false, confirmUrl: '', confirmName: '' }"
     @{{ $name }}-open.window="{{ $name }} = true; confirmUrl = $event.detail.url || '{{ $confirmUrl }}'; confirmName = $event.detail.name || ''">

    <div x-show="{{ $name }}" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display:none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                 @click="{{ $name }} = false"></div>

            {{-- Modal --}}
            <div class="relative bg-card-bg rounded-lg max-w-md w-full p-5 z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                {{-- Icon --}}
                <div class="flex items-center gap-4 mb-4">
                    @if($color === 'danger')
                    <div class="w-12 h-12 rounded-full bg-danger-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        </svg>
                    </div>
                    @elseif($color === 'warning')
                    <div class="w-12 h-12 rounded-full bg-warning-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/>
                        </svg>
                    </div>
                    @elseif($color === 'success')
                    <div class="w-12 h-12 rounded-full bg-success-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-full bg-accent-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                        </svg>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-heading">{{ $title }}</h3>
                        <p class="text-sm text-muted">This action cannot be undone.</p>
                    </div>
                </div>

                {{-- Message --}}
                <p class="text-sm text-body mb-6">{{ $message }} <span class="font-semibold" x-text="confirmName" x-show="confirmName"></span></p>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <button @click="{{ $name }} = false"
                            class="px-4 py-2 text-sm font-medium text-body bg-control-bg rounded-lg hover:bg-control-bg transition-colors">
                        Cancel
                    </button>
                    <form :action="confirmUrl" method="POST" class="inline">
                        @csrf
                        @if($method !== 'GET')
                        @method($method)
                        @endif
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors {{ $buttonClass }}">
                            {{ $confirmText }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>