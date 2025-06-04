<div class="flex flex-col h-screen bg-gray-50 z-50 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-20 h-20 bg-blue-200 rounded-full filter blur-xl animate-slow-fade">
        </div>
        <div
            class="absolute top-32 right-20 w-16 h-16 bg-purple-200 rounded-full filter blur-xl animate-slow-fade delay-500">
        </div>
        <div
            class="absolute bottom-20 left-1/4 w-24 h-24 bg-green-200 rounded-full filter blur-xl animate-slow-fade delay-1000">
        </div>
    </div>

    <div class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4">
            <a href="{{ route('dashboard') }}" wire:navigate.hover
                class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-all duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center ring-2 ring-white">
                        <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 rounded-full border-2 border-white">
                    </div>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-base">{{ $user->name }}</h2>
                    <p class="text-xs text-green-600 flex items-center">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>

            <button
                class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-all duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 relative">
        @foreach ($messages as $message)
            @if ($message['sender'] != auth()->user()->name)
                <div class="flex items-start space-x-3 animate-fade-in">
                    <div class="relative">
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full flex items-center justify-center flex-shrink-0 ring-1 ring-white">
                            <span class="text-white font-semibold text-xs">{{ substr($message['sender'], 0, 1) }}</span>
                        </div>
                        <div
                            class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-400 rounded-full border border-white">
                        </div>
                    </div>
                    <div class="flex-1">
                        <div
                            class="bg-white rounded-xl rounded-tl-md px-4 py-3 shadow-sm border border-gray-100 max-w-sm">
                            <p class="text-sm text-gray-800 leading-normal">{{ $message['message'] }}</p>
                        </div>
                        <div class="flex items-center space-x-2 mt-1 ml-1 text-xs text-gray-500">
                            <p class="font-medium">{{ $message['sender'] }}</p>
                            <span>&bull;</span>
                            <p>{{ \Carbon\Carbon::parse($message['created_at'])->timezone('Asia/Jakarta')->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-start justify-end space-x-3 animate-fade-in">
                    <div class="flex-1 flex justify-end">
                        <div class="relative">
                            <div class="bg-blue-500 rounded-xl rounded-tr-md px-4 py-3 shadow-sm max-w-sm">
                                <p class="text-sm text-white leading-normal">{{ $message['message'] }}</p>
                            </div>
                            <div class="flex items-center justify-end space-x-2 mt-1 mr-1 text-xs text-gray-300">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>{{ \Carbon\Carbon::parse($message['created_at'])->timezone('Asia/Jakarta')->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <form wire:submit="sendMessage()" class="sticky bottom-0 bg-white border-t border-gray-200 p-4 shadow-md">
        <div class="flex justify-between items-center space-x-3">
            <button type="button"
                class="flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full transition-all duration-200">
                <x-icons.file class="w-5 h-5" />
            </button>

            <div class="flex-1 relative">
                <textarea wire:model="message" placeholder="Ketik pesan..." rows="1" id="textMessage"
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl resize-none text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-blue-300 focus:bg-white transition-all duration-200"
                    style="outline: none;" required></textarea>

                <button type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <x-icons.emoji class="w-5 h-5" />
                </button>
            </div>

            <button type="submit"
                class="flex items-center justify-center w-12 h-12 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-all duration-200 transform hover:scale-105 active:scale-95"
                style="outline: none;">
                <x-icons.send class="w-5 h-5" />
            </button>
        </div>
    </form>
</div>
