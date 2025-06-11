<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    data-theme="{{ session('theme', 'light') }}"
    x-data="{
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        
        init() {
            this.applyTheme();
            this.setupEventListeners();
        },
        applyTheme() {
            document.documentElement.setAttribute('data-theme', this.theme);
            localStorage.setItem('theme', this.theme);
        },

        setupEventListeners() {
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (localStorage.getItem('theme') === null) {
                    this.theme = e.matches ? 'dark' : 'light';
                    this.applyTheme();
                }
            });

        // Listen for Livewire navigation events to maintain theme
            document.addEventListener('livewire:navigating', () => {
                localStorage.setItem('theme', this.theme);
            });

            document.addEventListener('livewire:navigated', () => {
                this.theme = localStorage.getItem('theme') || this.theme;
                this.applyTheme();
            });

            // Listen for theme updates from Livewire components
            window.addEventListener('theme-updated', (e) => {
                this.theme = e.detail.theme;
                this.applyTheme();
            });
        },
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.applyTheme();
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: this.theme }));
        }
    }" 
    x-init="init()"
@theme-updated.window="theme = $event.detail.theme; applyTheme()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kerajinan Tangan' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/filepond@4.30.4/dist/filepond.min.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview@4.6.11/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Theme persistence script for Livewire navigation -->
    <script>
        // Ensure theme is applied immediately on page load
        (function() {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);

            // Additional safeguard for Livewire navigation
            document.addEventListener('DOMContentLoaded', function() {
                // Reapply theme after DOM is fully loaded
                const currentTheme = localStorage.getItem('theme') || theme;
                document.documentElement.setAttribute('data-theme', currentTheme);
            });

            // Handle page visibility changes (when user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    const currentTheme = localStorage.getItem('theme') || theme;
                    document.documentElement.setAttribute('data-theme', currentTheme);
                }
            });
        })();
    </script>
</head>
<body class="flex flex-col min-h-screen bg-base-200 bg-grid-pattern">
    <div x-show="theme === 'light'">
        @livewire('header')
    </div>
    <div x-show="theme === 'dark'">
        @livewire('header-dark')
    </div>

    <!-- Theme Manager Component (Mobile Only) -->
    <div class="sm:hidden">
        @livewire('theme-manager')
    </div>
    
    @livewire('mobile-bottom-navigation')

    <main class="flex-grow pb-16 sm:pb-0">
        {{ $slot }}
    </main>
    
    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview@4.6.11/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type@1.2.8/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size@2.2.8/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond@4.30.4/dist/filepond.min.js"></script>
    <script>
        window.addEventListener('livewire:init', () => {
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileValidateType,
                FilePondPluginFileValidateSize
            );
        });
    </script>
    @livewireScripts
    @livewire('footer')
</body>
</html>