<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('General Chat') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Berbicara dengan semua pengguna dalam ruang forum global
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center px-3 py-2 bg-green-50 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm font-medium text-green-700">Online</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div style="height: calc(100vh - 200px);">
                <livewire:global-chat-component />
            </div>
        </div>
    </div>
</x-app-layout>
