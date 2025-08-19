<?php

namespace App\Http\Controllers;

use App\Models\Text;
use App\Models\Paragraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextAnalyzerController extends Controller
{
    public function index()
    {
        $texts = Text::with('paragraph')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('analyzer.index', compact('texts'));
    }

    public function create()
    {
        return view('analyzer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_text' => 'required|string|min:10',
        ], [
            'original_text.required' => 'Text is required',
            'original_text.min' => 'Text must be more than 10 characters',
        ]);

        try {
            // Save the original text
            $text = new Text();
            $text->user_id = auth()->id();
            $text->original_text = $request->original_text;
            $text->save();

            $analysisResult = $this->analyzeWithGemini($request->original_text);

            // Save analysis results clearly
            $paragraph = new Paragraph();
            $paragraph->text_id = $text->id;
            $paragraph->title_fr = $analysisResult['titleFR'] ?? null;
            $paragraph->title_ar = $analysisResult['titleAR'] ?? null;
            $paragraph->subtitle_fr = $analysisResult['subtitleFR'] ?? null;
            $paragraph->subtitle_ar = $analysisResult['subtitleAR'] ?? null;

            // Save paragraphs clearly and in readable format
            if (isset($analysisResult['paragraphs'])) {
                $paragraph->paragraphs = $this->formatParagraphsForDatabase($analysisResult['paragraphs']);
            }

            $paragraph->save();

            return redirect()->route('analyzer.show', $text->id)->with('success', 'تم تحليل النص بنجاح');

        } catch (\Exception $e) {
            Log::error('Text analysis error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'حدث خطأ في تحليل النص: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $text = Text::with('paragraph')->findOrFail($id);

        if ($text->user_id !== auth()->id()) {
            abort(403);
        }

        return view('analyzer.show', compact('text'));
    }
public function destroy($id)
{
    $text = Text::findOrFail($id);


    if ($text->user_id !== auth()->id()) {
        abort(403);
    }


    if ($text->paragraph) {
        $text->paragraph->delete();
    }


    $text->delete();

    return redirect()->route('analyzer.index')->with('success', 'تم حذف النص بنجاح');
}

    /**
     * Format paragraphs for database storage in a clear way
     */
    private function formatParagraphsForDatabase($paragraphs)
    {
        $formatted = [];

        foreach ($paragraphs as $section) {
            $formattedSection = [];
            foreach ($section as $item) {
                $formattedItem = [
                    'id' => $item['id'] ?? uniqid(),
                    'phraseFR' => $item['phraseFR'] ?? '',
                    'phraseAR' => $item['phraseAR'] ?? '',
                    'grammarDetails' => $item['grammarDetails'] ?? []
                ];
                $formattedSection[] = $formattedItem;
            }
            $formatted[] = $formattedSection;
        }

        return $formatted;
    }

    /**
     * Decode Unicode sequences
     */
    private function decodeUnicode($text)
    {
        if (!is_string($text)) {
            return $text;
        }

        // Decode Unicode sequences
        $decoded = json_decode('"' . $text . '"', true);
        return $decoded !== null ? $decoded : $text;
    }

    private function analyzeWithGemini($text)
    {
        $apiKey = config('app.gemini_api_key', env('GEMINI_API_KEY'));

        if (!$apiKey) {
            throw new \Exception('Gemini API key is not set');
        }

        $prompt = $this->generatePrompt($text);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent", [
            'contents' => [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception("Server connection failed: " . $response->status());
        }

        $responseData = $response->json();

        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception('Invalid response from Gemini API');
        }

        $analysisText = $responseData['candidates'][0]['content']['parts'][0]['text'];

        // Clean the text
        $analysisText = trim($analysisText);
        $analysisText = preg_replace('/^```json\s*/m', '', $analysisText);
        $analysisText = preg_replace('/\s*```$/m', '', $analysisText);
        $analysisText = trim($analysisText);

        // Extract JSON
        $jsonStart = strpos($analysisText, '{');
        $jsonEnd = strrpos($analysisText, '}');

        if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
            $analysisText = substr($analysisText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        // Decode JSON while preserving Arabic and French characters
        $analysisResult = json_decode($analysisText, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Error: ' . json_last_error_msg());
            Log::error('Raw response: ' . $analysisText);
            throw new \Exception('JSON parsing error: ' . json_last_error_msg());
        }

        return $analysisResult;
    }

  private function generatePrompt($text)
{
    return "
You are a bilingual linguistic analyst specialized in **French and Arabic**.
Analyze the following French text and return the result in accurate JSON format as specified below.

IMPORTANT:
- Return all text in clear, readable format - NO Unicode escaping (\\u codes).
- Use actual Arabic and French characters directly.
- For each word, provide BOTH the French grammatical analysis AND the Arabic translation + its grammatical role.

Required:
1. Provide a short suitable title for the text in **French** and its **Arabic translation**
2. Provide a short subtitle in **French** and its **Arabic translation**
3. Split the text into logical sentences
4. For each sentence:
   - Original sentence in French
   - Its full translation in Arabic
   - For each word: write its grammatical parsing in French + its translation + Arabic grammatical explanation

Strict JSON format (with actual characters, not Unicode escapes):
{
  \"titleFR\": \"[Short suitable title in French]\",
  \"titleAR\": \"[Arabic translation of the title]\",
  \"subtitleFR\": \"[Short subtitle in French]\",
  \"subtitleAR\": \"[Arabic translation of the subtitle]\",
  \"paragraphs\": [
    [
      {
        \"id\": \"01\",
        \"phraseFR\": \"[First French sentence]\",
        \"phraseAR\": \"[Arabic translation of the sentence]\",
        \"grammarDetails\": [
          \"[French Word1]: [French parsing] (الترجمة + التحليل بالعربية)\",
          \"[French Word2]: [French parsing] (الترجمة + التحليل بالعربية)\",
          \"...etc\"
        ]
      }
    ]
  ]
}

French text for analysis:
$text

Make sure to return **only valid JSON**, with actual readable characters (not Unicode escapes), and no additional text before or after the JSON.
";
    }

}
