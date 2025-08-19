<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">

                <!-- Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Analyzed Texts</h1>
                            <p class="text-gray-600 mt-1">A list of all analyzed texts</p>
                        </div>
                        <a href="{{ route('analyzer.create') }}"
                           class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            + Analyze New Text
                        </a>
                    </div>
                </div>

                <!-- Success message -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($texts->count() > 0)
                <!-- Dashboard cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

                    <!-- Total Texts -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-xl border-r-4 border-blue-500 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">

                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-full shadow-lg mr-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="text-2xl text-gray-700 font-semibold">Total</div>
                                    <div class="text-2xl font-bold text-blue-700">{{ $texts->total() }}</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: 100%"></div>
                                    </div>
                                    <span class="text-sm text-blue-600 font-bold mr-3 bg-blue-50 px-2 py-1 rounded-full">100%</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Today's Texts -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-xl border-r-4 border-green-500 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                                        <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-full shadow-lg mr-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="text-2xl text-gray-700 font-semibold">Today</div>
                                    <div class="text-2xl font-bold text-green-700">{{ $texts->where('created_at', '>=', today())->count() }}</div>
                                </div>
                                @php
                                    $todayCount = $texts->where('created_at', '>=', today())->count();
                                    $todayPercentage = $texts->total() > 0 ? ($todayCount / $texts->total()) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $todayPercentage }}%"></div>
                                    </div>
                                    <span class="text-sm {{ $todayPercentage > 20 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} font-bold mr-3 px-2 py-1 rounded-full">
                                        {{ number_format($todayPercentage, 1) }}%
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--  Week -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-xl border-r-4 border-yellow-500 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-4 rounded-full shadow-lg mr-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="text-2xl text-gray-700 font-semibold"> Week</div>
                                    <div class="text-2xl font-bold text-yellow-700">{{ $texts->where('created_at', '>=', now()->startOfWeek())->count() }}</div>
                                </div>
                                @php
                                    $weekCount = $texts->where('created_at', '>=', now()->startOfWeek())->count();
                                    $weekPercentage = $texts->total() > 0 ? ($weekCount / $texts->total()) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full transition-all duration-500" style="width: {{ $weekPercentage }}%"></div>
                                    </div>
                                    <span class="text-sm {{ $weekPercentage > 50 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} font-bold mr-3 px-2 py-1 rounded-full">
                                        {{ number_format($weekPercentage, 1) }}%
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--  Month -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-xl border-r-4 border-red-500 hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                                        <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-full shadow-lg mr-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="text-2xl text-gray-700 font-semibold"> Month</div>
                                    <div class="text-2xl font-bold text-red-700">{{ $texts->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                                </div>
                                @php
                                    $monthCount = $texts->where('created_at', '>=', now()->startOfMonth())->count();
                                    $monthPercentage = $texts->total() > 0 ? ($monthCount / $texts->total()) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full transition-all duration-500" style="width: {{ $monthPercentage }}%"></div>
                                    </div>
                                    <span class="text-sm {{ $monthPercentage > 70 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} font-bold mr-3 px-2 py-1 rounded-full">
                                        {{ number_format($monthPercentage, 1) }}%
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                    <!-- Text list -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                            <h2 class="text-lg font-bold text-gray-900">Analyzed Text List</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($texts as $index => $text)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <!-- Text info -->
                                        <div class="flex-1">
                                            <!-- Text number -->
                                            <div class="flex items-center mb-3">
                                                <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                                    Text #{{ ($texts->currentPage() - 1) * $texts->perPage() + $index + 1 }}
                                                </span>
                                            </div>

                                            <!-- Text title -->
                                            @if($text->paragraph)
                                                <div class="mb-3">
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-1">
                                                        {{ $text->paragraph->title_fr }}
                                                    </h3>
                                                    @if($text->paragraph->title_ar)
                                                        <h4 class="text-lg text-purple-700 font-medium">
                                                            {{ $text->paragraph->title_ar }}
                                                        </h4>
                                                    @endif
                                                    @if($text->paragraph->subtitle_fr)
                                                        <p class="text-gray-600 text-sm mt-1">
                                                            {{ $text->paragraph->subtitle_fr }}
                                                            @if($text->paragraph->subtitle_ar)
                                                                | {{ $text->paragraph->subtitle_ar }}
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Text preview -->
                                            <div class="mb-4 bg-gray-50 p-4 rounded-lg border-l-4 border-gray-300">
                                                <p class="text-gray-700 leading-relaxed">
                                                    {{ Str::limit($text->original_text, 150) }}
                                                </p>
                                            </div>

                                            <!-- Colored info -->
                                            <div class="flex items-center text-sm space-x-4 space-x-reverse">
                                                <span class="flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-bold shadow-sm">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $text->created_at->format('d/m/Y') }}
                                                </span>
                                                <span class="flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold shadow-sm">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $text->created_at->format('H:i') }}
                                                </span>
                                                <span class="flex items-center bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-bold shadow-sm">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ Str::wordCount($text->original_text) }} words
                                                </span>
                                            </div>
                                        </div>

<!-- Action buttons -->
<div class="ml-6 flex-shrink-0 flex flex-col space-y-4">
    <!-- Show Button -->
    <a href="{{ route('analyzer.show', $text->id) }}"
       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
        <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <span class="ml-3 text-indigo-50">Show Analysis</span>
    </a>

    <!-- Delete Button -->
    <form action="{{ route('analyzer.destroy', $text->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                onclick="return confirm('Are you sure you want to delete this analysis?')">
            <svg class="w-5 h-5 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <span class="ml-3 text-rose-50">Delete</span>
        </button>
    </form>
</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 bg-white p-4 rounded-lg shadow-sm">
                        {{ $texts->links() }}
                    </div>
                @else
                    <!-- Empty state -->
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <div class="max-w-sm mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Analyzed Texts</h3>
                            <p class="text-gray-600 mb-6">You haven't analyzed any text yet. Start now!</p>
                            <a href="{{ route('analyzer.create') }}"
                               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Analyze Your First Text
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
