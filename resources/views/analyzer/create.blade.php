<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Main Analysis Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 mb-8 transform hover:scale-[1.02] transition-all duration-300">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-4">
                        <i class="fas fa-language text-blue-500 mr-3"></i>
                        French Text Analysis Studio
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Advanced linguistic analysis with AI-powered insights</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 animate-pulse">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Errors found:</strong>
                        </div>
                        <ul class="mt-2">
                            @foreach($errors->all() as $error)
                                <li class="ml-4">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('analyzer.store') }}" method="POST" id="analyzerForm">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Text Input Section -->
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <label for="original_text" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <i class="fas fa-edit mr-2 text-blue-500"></i>
                                    Enter French Text
                                </label>
                                <div class="relative">
                                    <textarea
                                        id="original_text"
                                        name="original_text"
                                        rows="12"
                                        class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:text-white resize-none transition-all duration-300"
                                        placeholder="Écrivez votre texte français ici...

Exemple:
Bonjour! Je m'appelle Marie et j'habite à Paris. J'aime beaucoup la littérature française et je lis souvent des romans classiques."
                                        required
                                        oninput="updateStats()"
                                    >{{ old('original_text') }}</textarea>
                                    <div class="absolute bottom-2 right-2 text-sm text-gray-500 dark:text-gray-400" id="charCount">
                                        0 characters
                                    </div>
                                </div>
                            </div>

                            <!-- Analysis Options -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    <i class="fas fa-cogs mr-2 text-green-500"></i>
                                    Analysis Options
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="analysis_options[]" value="grammar" class="rounded text-blue-500 mr-2" checked>
                                        <span class="text-sm dark:text-gray-300">Grammar</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="analysis_options[]" value="vocabulary" class="rounded text-blue-500 mr-2" checked>
                                        <span class="text-sm dark:text-gray-300">Vocabulary</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="analysis_options[]" value="sentiment" class="rounded text-blue-500 mr-2">
                                        <span class="text-sm dark:text-gray-300">Sentiment</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="analysis_options[]" value="readability" class="rounded text-blue-500 mr-2">
                                        <span class="text-sm dark:text-gray-300">Readability</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Difficulty Level -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-layer-group mr-2 text-purple-500"></i>
                                    Analysis Depth
                                </label>
                                <select name="difficulty_level" class="w-full px-3 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="basic">Basic Analysis</option>
                                    <option value="intermediate" selected>Intermediate Analysis</option>
                                    <option value="advanced">Advanced Analysis</option>
                                    <option value="expert">Expert Level</option>
                                </select>
                            </div>
                        </div>

                        <!-- Live Statistics Panel -->
                        <div class="lg:col-span-1">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 sticky top-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                    <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                                    Live Statistics
                                </h3>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-font mr-2"></i>Characters
                                        </span>
                                        <span class="font-bold text-blue-600" id="liveCharCount">0</span>
                                    </div>

                                    <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-align-left mr-2"></i>Words
                                        </span>
                                        <span class="font-bold text-green-600" id="liveWordCount">0</span>
                                    </div>

                                    <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-paragraph mr-2"></i>Sentences
                                        </span>
                                        <span class="font-bold text-purple-600" id="liveSentenceCount">0</span>
                                    </div>

                                    <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-clock mr-2"></i>Est. Reading
                                        </span>
                                        <span class="font-bold text-orange-600" id="liveReadingTime">0 min</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-6">
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                                        <span>Text Completeness</span>
                                        <span id="progressPercent">0%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: 0%" id="progressBar"></div>
                                    </div>
                                </div>

                                <!-- Sample Text Suggestions -->
                                <div class="mt-6">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                                        Quick Samples
                                    </h4>
                                    <div class="space-y-2">
                                        <button type="button" onclick="insertSampleText('beginner')"
                                                class="w-full text-left text-xs bg-white dark:bg-gray-800 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            📚 Beginner Text
                                        </button>
                                        <button type="button" onclick="insertSampleText('intermediate')"
                                                class="w-full text-left text-xs bg-white dark:bg-gray-800 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            📖 Intermediate Text
                                        </button>
                                        <button type="button" onclick="insertSampleText('advanced')"
                                                class="w-full text-left text-xs bg-white dark:bg-gray-800 p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            📝 Advanced Text
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8">
                        <div class="flex gap-3">
                            <a href="{{ route('analyzer.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                            <button type="button" onclick="clearText()"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                                <i class="fas fa-trash mr-2"></i>Clear
                            </button>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="saveAsDraft()"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg transition-all">
                                <i class="fas fa-save mr-2"></i>Save Draft
                            </button>
                            <button type="submit" id="analyzeBtn"
                                    class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                                <i class="fas fa-magic mr-2"></i>Analyze Text
                                <span class="animate-pulse ml-2" id="loadingSpinner" style="display: none;">⚡</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Quick Tips Card -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                    Pro Tips for Better Analysis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Quality Text</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Use complete sentences with proper punctuation</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-ruler text-blue-500 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-gray-800 dark:text-white">Optimal Length</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">50-500 words for best analysis results</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-language text-purple-500 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-gray-800 dark:text-white">French Only</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Ensure text is primarily in French</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample texts for different levels
        const sampleTexts = {
            beginner: "Bonjour! Je m'appelle Sophie et j'ai vingt ans. J'habite à Lyon avec ma famille. J'aime beaucoup lire des livres et écouter de la musique. Le weekend, je vais au cinéma avec mes amis. Nous aimons regarder des films français.",
            intermediate: "La France est un pays riche en histoire et en culture. Paris, sa capitale, est connue dans le monde entier pour ses monuments comme la Tour Eiffel et le Louvre. La cuisine française est également réputée pour sa qualité et sa diversité. Chaque région a ses propres spécialités culinaires qui reflètent les traditions locales.",
            advanced: "L'évolution technologique contemporaine soulève des questions philosophiques profondes concernant l'avenir de l'humanité. L'intelligence artificielle, en particulier, bouleverse notre compréhension traditionnelle du travail, de la créativité et même de la conscience. Ces transformations nécessitent une réflexion éthique approfondie sur les implications sociétales de ces innovations révolutionnaires."
        };

        // Update live statistics
        function updateStats() {
            const text = document.getElementById('original_text').value;
            const charCount = text.length;
            const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
            const sentenceCount = text.split(/[.!?]+/).filter(s => s.trim().length > 0).length;
            const readingTime = Math.ceil(wordCount / 200); // Assuming 200 words per minute

            document.getElementById('charCount').textContent = `${charCount} characters`;
            document.getElementById('liveCharCount').textContent = charCount;
            document.getElementById('liveWordCount').textContent = wordCount;
            document.getElementById('liveSentenceCount').textContent = sentenceCount;
            document.getElementById('liveReadingTime').textContent = `${readingTime} min`;

            // Update progress bar (assuming 100 words as "complete")
            const progress = Math.min((wordCount / 100) * 100, 100);
            document.getElementById('progressBar').style.width = `${progress}%`;
            document.getElementById('progressPercent').textContent = `${Math.round(progress)}%`;
        }

        // Insert sample text
        function insertSampleText(level) {
            document.getElementById('original_text').value = sampleTexts[level];
            updateStats();
        }

        // Clear text
        function clearText() {
            if (confirm('Are you sure you want to clear the text?')) {
                document.getElementById('original_text').value = '';
                updateStats();
            }
        }

        // Save as draft (placeholder)
        function saveAsDraft() {
            const text = document.getElementById('original_text').value;
            if (text.trim()) {
                localStorage.setItem('frenchTextDraft', text);
                alert('Draft saved successfully!');
            } else {
                alert('No text to save!');
            }
        }

        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('frenchTextDraft');
            if (draft && !document.getElementById('original_text').value) {
                if (confirm('Load saved draft?')) {
                    document.getElementById('original_text').value = draft;
                    updateStats();
                }
            }
        });

        // Form submission with loading animation
        document.getElementById('analyzerForm').addEventListener('submit', function() {
            document.getElementById('loadingSpinner').style.display = 'inline';
            document.getElementById('analyzeBtn').disabled = true;
        });

        // Dark mode toggle
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            this.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            localStorage.setItem('darkMode', isDark);
        });

        // Load dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
            document.getElementById('darkModeToggle').innerHTML = '<i class="fas fa-sun"></i>';
        }

        // Initialize stats
        updateStats();
    </script>
</x-app-layout>
