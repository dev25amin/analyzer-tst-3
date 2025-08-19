<x-app-layout>
    <x-slot name="header"></x-slot>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Interactive Text Analysis</h1>
            <a href="{{ route('analyzer.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($text->paragraph)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div id="interactive-text" class="text-lg leading-relaxed"></div>
            </div>
        @else
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                No analysis found for this text.
            </div>
        @endif
    </div>
</div>

<style>
    .sentence {
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: inline;
    }
    .sentence:hover {
        background-color: #f3f4f6;
    }

    .word {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: inline;
    }
    .word:hover {
        background-color: #dbeafe;
    }

    .sentence-block {
        display: inline;
        position: relative;
        vertical-align: baseline;
        margin-right: 4px;
        white-space: normal;
    }

.translation {
    background-color: #dcfce7;
    padding: 12px;
    border-radius: 6px;
    border-right: 4px solid #22c55e;
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    white-space: normal;
    z-index: 10;
    min-width: max-content;
    max-width: 100vw;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.grammar-analysis {
    background-color: #fef08a;
    padding: 10px;
    border-radius: 6px;
    border-right: 4px solid #eab308;
    display: none;
    font-size: 14px;
    position: absolute;
    top: calc(100% + 55px);
    left: 0;
    white-space: normal;
    z-index: 10;
    min-width: max-content;
    max-width: 100vw;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

    .active-sentence-blue {
        background-color: #bfdbfe !important;
        border: 2px solid #3b82f6;
    }

    .active-word {
        background-color: #fef08a !important;
        border: 1px solid #eab308 !important;
        font-weight: bold;
        font-size: 1.2em;
    }

    .analysis-translation {
        margin-top: 4px;
        padding: 4px 6px;
        background: #dcfce7;
        border-radius: 4px;
        font-size: 13px;
        color: #065f46;
    }
</style>

<script>
    const textData = {
        originalText: `{{ $text->original_text }}`,
        analysis: @json($text->paragraph ? collect($text->paragraph->paragraphs)->flatten(1)->map(function($item) {
            return [
                'phraseFR' => $item['phraseFR'] ?? '',
                'phraseAR' => $item['phraseAR'] ?? '',
                'grammarDetails' => $item['grammarDetails'] ?? []
            ];
        })->toArray() : [])
    };

    function createInteractiveText() {
        const container = document.getElementById('interactive-text');
        let htmlContent = '';

        if (!textData.analysis || textData.analysis.length === 0) {
            container.innerHTML = '<p class="text-gray-600">No analysis data available.</p>';
            return;
        }

        textData.analysis.forEach((sentence, sentenceIndex) => {
            if (!sentence.phraseFR) return;

            htmlContent += `<span class="sentence-block">
                <span class="sentence" onclick="toggleTranslation(${sentenceIndex})" id="sentence-${sentenceIndex}">`;

            const words = sentence.phraseFR.split(/(\s+|[.!?,:;])/);
            let actualWordIndex = 0;

            words.forEach((word) => {
                if (word.trim() && !/^\s+$/.test(word) && !/^[.!?,:;]+$/.test(word)) {
                    htmlContent += `
                        <span class="word" onclick="toggleWordAnalysis(${sentenceIndex}, ${actualWordIndex}, '${word.replace(/'/g, "\\'")}', event)" id="word-${sentenceIndex}-${actualWordIndex}">
                            ${word}
                        </span>`;
                    actualWordIndex++;
                } else {
                    htmlContent += `<span>${word}</span>`;
                }
            });

            htmlContent += `</span>
                <div class="translation" id="translation-${sentenceIndex}">
                    <strong></strong> ${sentence.phraseAR || 'Not available'}
                </div>
<div class="grammar-analysis" id="analysis-${sentenceIndex}">
    <div id="word-analysis-content-${sentenceIndex}">
        ${sentence.grammarDetails && sentence.grammarDetails.length > 0
            ? `<ul class="list-disc list-inside space-y-1">${sentence.grammarDetails.map(detail => `
                <li>
                    ${detail}
                    <div class="analysis-translation">${sentence.phraseAR || 'Not available'}</div>
                </li>`).join('')}
               </ul>`
            : '<p class="text-gray-600">No grammar analysis available.</p>'}
    </div>
</div>

            </span> `;
        });

        container.innerHTML = htmlContent;
    }

    function toggleTranslation(sentenceIndex) {
        const translation = document.getElementById(`translation-${sentenceIndex}`);
        const sentence = document.getElementById(`sentence-${sentenceIndex}`);

        if (translation.style.display === 'none' || translation.style.display === '') {
            translation.style.display = 'block';
            sentence.classList.add('active-sentence-blue');
        } else {
            translation.style.display = 'none';
            sentence.classList.remove('active-sentence-blue');
        }
    }

    function toggleWordAnalysis(sentenceIndex, wordIndex, wordText, event) {
        event.stopPropagation();
        const analysis = document.getElementById(`analysis-${sentenceIndex}`);
        const translation = document.getElementById(`translation-${sentenceIndex}`);
        const wordAnalysisContent = document.getElementById(`word-analysis-content-${sentenceIndex}`);
        const sentence = document.getElementById(`sentence-${sentenceIndex}`);

        document.querySelectorAll('.grammar-analysis').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.translation').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.word').forEach(el => el.classList.remove('active-word'));
        document.querySelectorAll('.sentence').forEach(el => el.classList.remove('active-sentence-blue'));

        translation.style.display = 'block';
        analysis.style.display = 'block';
        sentence.classList.add('active-sentence-blue');
        event.target.classList.add('active-word');

        const sentenceData = textData.analysis[sentenceIndex];
        const grammarDetails = sentenceData.grammarDetails || [];
        let wordSpecificAnalysis = '';
        const cleanWordText = wordText.toLowerCase().replace(/[.,!?;:'"]/g, '');

        grammarDetails.forEach(detail => {
            const detailLower = detail.toLowerCase();
            if (detailLower.startsWith(cleanWordText + ':') || detailLower.includes(cleanWordText + ':')) {
                wordSpecificAnalysis += `<li class="highlighted-analysis">${detail}</li>`;
            }
        });

        if (wordSpecificAnalysis) {
            wordAnalysisContent.innerHTML = `<ul class="list-disc list-inside space-y-1">${wordSpecificAnalysis}</ul>`;
        } else {
            wordAnalysisContent.innerHTML = `<p class="text-gray-600">No specific analysis available for the word "${wordText}"</p>`;
        }
    }

    document.addEventListener('DOMContentLoaded', createInteractiveText);

    document.addEventListener('click', function (event) {
        const clickedInside = event.target.closest('.sentence-block');
        if (!clickedInside) {
            document.querySelectorAll('.translation').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.grammar-analysis').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.word').forEach(el => el.classList.remove('active-word'));
            document.querySelectorAll('.sentence').forEach(el => el.classList.remove('active-sentence-blue'));
        }
    });
</script>
</x-app-layout>
