<div class="flex flex-col h-full bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" style="max-height: calc(100vh - 280px);" x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight } }"
        x-init="scrollToBottom()" wire:poll.750ms="loadMessages" x-effect="scrollToBottom()">

        @if (empty($messages))
            <div class="flex flex-col items-center justify-center py-16 text-gray-500">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-lg font-medium mb-2">Belum ada pesan</p>
                <p class="text-sm">Jadilah yang pertama mengirim pesan di chat general!</p>
            </div>
        @else
            @foreach ($messages as $message)
                <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-start space-x-3 max-w-xs lg:max-w-md">
                        @if (!$message['is_mine'])
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm overflow-hidden">
                                    @if ($message['profile_photo'] ?? null)
                                        <img src="{{ asset('storage/' . $message['profile_photo']) }}"
                                            alt="{{ $message['user_name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-xs font-semibold text-white">
                                                {{ substr($message['user_name'], 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col {{ $message['is_mine'] ? 'items-end' : 'items-start' }}">
                            @if (!$message['is_mine'])
                                <span class="text-xs font-medium text-gray-600 mb-1">{{ $message['user_name'] }}</span>
                            @endif

                            <div
                                class="px-4 py-2 rounded-2xl {{ $message['is_mine'] ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' : 'bg-gray-100 text-gray-800' }} shadow-sm">
                                <p class="text-sm">{{ $message['message'] }}</p>

                                @if ($message['file_path'])
                                    <div
                                        class="mt-2 pt-2 border-t {{ $message['is_mine'] ? 'border-blue-400' : 'border-gray-200' }}">
                                        <x-file-attachment :file-path="$message['file_path']" :file-name="$message['file_name'] ?? 'Attachment'" :file-type="$message['file_type'] ?? ''"
                                            :file-size="$message['file_size'] ?? 0" :is-outgoing="$message['is_mine']" />
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center mt-1 space-x-1">
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->timezone('Asia/Jakarta')->format('H:i') }}
                                </span>
                                @if ($message['is_mine'])
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        @if ($message['is_mine'])
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm overflow-hidden">
                                    @if (auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                            alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                            <span class="text-xs font-semibold text-white">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Message Input -->
    <form wire:submit="sendMessage" class="border-t border-gray-200 p-4 bg-gray-50">
        <div class="flex items-center space-x-3">
            <div>
                <input type="file" wire:model="attachment" id="global-attachment" class="hidden"
                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar" />
                <label for="global-attachment"
                    class="flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full transition-all duration-200 cursor-pointer">
                    <x-icons.file class="w-5 h-5" />
                </label>
                <div wire:loading wire:target="attachment" class="mt-1 text-xs text-gray-600">
                    Uploading...
                </div>
            </div>

            <div class="flex-1 relative">
                <textarea wire:model="message" placeholder="Ketik pesan untuk semua orang..." rows="1" id="textMessage"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-2xl resize-none text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200"
                    maxlength="1000"></textarea>

                <button type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <x-icons.emoji class="w-5 h-5" />
                </button>
            </div>

            <button type="submit" x-bind:disabled="$wire.uploading"
                class="flex items-center justify-center w-12 h-12 bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-200 disabled:opacity-75 disabled:cursor-not-allowed"
                style="outline: none;">
                <div wire:loading.remove wire:target="sendMessage">
                    <x-icons.send class="w-5 h-5" />
                </div>
                <div wire:loading wire:target="sendMessage">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </button>
        </div>

        @if ($attachment)
            <div class="mt-2 p-2 bg-gray-100 rounded-lg flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    <span class="text-sm truncate">{{ $attachment->getClientOriginalName() }}</span>
                </div>
                <button type="button" wire:click="$set('attachment', null)" class="text-gray-500 hover:text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @error('message')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror

        @error('attachment')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </form>
</div>
