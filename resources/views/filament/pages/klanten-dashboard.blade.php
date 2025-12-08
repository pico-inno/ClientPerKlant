<x-filament-panels::page>
    {{-- Page content --}}
    @if ($this->isFullscreen())
        <style>
            .fi-sidebar,
            .fi-topbar {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            .fi-main,
            .filament-main,
            .fi-content {
                margin-left: 0 !important;
                margin-top: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            html,
            body,
            .fi-main,
            .filament-main,
            .fi-content {
                overflow: auto !important;
                height: auto !important;
            }

            body {
                overflow-y: auto !important;
            }
        </style>
    @endif
</x-filament-panels::page>
