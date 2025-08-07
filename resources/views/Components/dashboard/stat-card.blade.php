<div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6">
    <div class="bg-gradient-to-br {{ $bgGradient ?? 'from-blue-500 to-blue-600' }} text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 h-full card-hover">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h5 class="text-lg font-semibold mb-2 opacity-90">{{ $title }}</h5>
                    <h2 class="text-3xl font-bold mb-0" id="{{ $id }}">{{ $value ?? 0 }}</h2>
                </div>
                <div class="ml-4">
                    <i class="{{ $icon }} text-3xl opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-black bg-opacity-10 rounded-b-xl">
            @if($link)
                <a href="{{ $link }}" class="text-white hover:text-gray-200 transition-colors flex items-center justify-between group">
                    <span>{{ $footerText }}</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            @else
                <span class="text-white">{{ $footerText }}</span>
            @endif
        </div>
    </div>
</div>
