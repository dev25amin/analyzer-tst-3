<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard - French Text Analyzer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Welcome -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                            Welcome {{ Auth::user()->name }}! 👋
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Welcome to the Advanced French Text Analyzer
                        </p>
                    </div>

                    <!-- Quick Statistics -->
                    @php
                        $userTextsCount = \App\Models\Text::where('user_id', Auth::id())->count();
                        $recentTexts = \App\Models\Text::where('user_id', Auth::id())
                                                     ->with('paragraph')
                                                     ->latest()
                                                     ->take(3)
                                                     ->get();
                    @endphp

                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg text-center">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                {{ $userTextsCount }}
                            </div>
                            <p class="text-blue-800 dark:text-blue-300">Analyzed Texts</p>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg text-center">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                                {{ $recentTexts->sum(function($text) {
                                    return $text->paragraph && is_array($text->paragraph->paragraphs) && isset($text->paragraph->paragraphs[0])
                                        ? count($text->paragraph->paragraphs[0])
                                        : 0;
                                }) }}
                            </div>
                            <p class="text-green-800 dark:text-green-300">Analyzed Sentences</p>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg text-center">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                                🎯
                            </div>
                            <p class="text-purple-800 dark:text-purple-300">Accurate Analysis</p>
                        </div>
                    </div>

                    <!-- Quick Buttons -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <a href="{{ route('analyzer.create') }}"
                           class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105 block text-center">
                            <div class="text-4xl mb-3">🚀</div>
                            <h4 class="text-xl font-semibold mb-2">Analyze New Text</h4>
                            <p class="text-blue-100">Start analyzing a new French text</p>
                        </a>

                        <a href="{{ route('analyzer.index') }}"
                           class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105 block text-center">
                            <div class="text-4xl mb-3">📚</div>
                            <h4 class="text-xl font-semibold mb-2">Analyzed Texts</h4>
                            <p class="text-green-100">Review all analyzed texts</p>
                        </a>
                    </div>

                    <!-- Recent Texts -->
                    @if($recentTexts->count() > 0)
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            📄 Latest Analyzed Texts
                        </h4>
                        <div class="space-y-4">
                            @foreach($recentTexts as $text)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        @if($text->paragraph)
                                            <h5 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">
                                                {{ $text->paragraph->title_ar }}
                                            </h5>
                                        @endif
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            {{ Str::limit($text->original_text, 100) }}
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $text->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <a href="{{ route('analyzer.show', $text->id) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm ml-4">
                                        View
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>


                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
