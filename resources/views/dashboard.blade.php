<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Percakapan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Mulai mengobrol dengan orang di sekitar Anda
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
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Percakapan Terbaru</h3>
                </div>

                <div class="max-h-[600px] overflow-y-auto">
                    @forelse ($users as $user)
                        <div class="group hover:bg-gray-50 transition-colors duration-200">
                            <a href="{{ route('chat', $user->id) }}"
                                class="flex items-center px-6 py-4 border-b border-gray-100 last:border-b-0"
                                wire:navigate.hover>

                                <div class="relative flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                        <span
                                            class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 rounded-full border-2 border-white dark:border-slate-800">
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0 ml-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="text-sm font-semibold text-slate-900">
                                            {{ $user->name }}
                                        </h3>
                                        <span class="text-xs text-slate-500">Baru saja</span>
                                    </div>
                                    <p class="text-sm text-slate-600 truncate">
                                        {{ $user->last_message ?? 'Mulai percakapan...' }}
                                    </p>
                                </div>

                                <div class="flex-shrink-0 ml-4">
                                    <div
                                        class="w-2 h-2 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 px-6">
                            <div
                                class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900-3">Belum ada percakapan</h3>
                            <p class="text-slate-600 text-center mb-8 max-w-md">
                                Selamat datang di Chat! Mulai percakapan pertama Anda dan terhubung dengan orang lain.
                            </p>
                            <button
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Mulai Chat Pertama
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
