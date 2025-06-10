<a href="{{ asset('storage/' . $filePath) }}" target="_blank"
    class="block {{ $isOutgoing ? 'text-white hover:text-blue-100' : 'text-blue-500 hover:text-blue-700' }} transition-colors">
    @if ($isImage())
        <div class="mt-2">
            <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $fileName }}"
                class="max-w-full h-auto rounded-lg max-h-48 object-cover">
            <p class="text-xs {{ $isOutgoing ? 'text-blue-200' : 'text-gray-500' }} mt-1">
                {{ $fileName }} ({{ $formattedSize() }})
            </p>
        </div>
    @else
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <div>
                <span class="text-sm">{{ $fileName }}</span>
                <p class="text-xs {{ $isOutgoing ? 'text-blue-200' : 'text-gray-500' }}">{{ $formattedSize() }}</p>
            </div>
        </div>
    @endif
</a>
