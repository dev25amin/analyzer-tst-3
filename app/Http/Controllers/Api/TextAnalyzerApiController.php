<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Text;
use App\Models\Paragraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TextAnalyzerApiController extends Controller
{
    /**
     * Send Text for Analysis
     * POST /api/v1/send-text
     */
    public function sendText(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_text' => 'required|string|min:10|max:5000',
        ], [
            'original_text.required' => 'Text is required',
            'original_text.min' => 'The text must be more than 10 characters',
            'original_text.max' => 'The text must be less than 5000 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Save original text
            $text = Text::create([
                'user_id' => auth()->id(),
                'original_text' => $request->original_text,
            ]);

            // Analyze text using Gemini
            $analysisResult = $this->analyzeWithGemini($request->original_text);

            // Save analysis result
            $paragraph = Paragraph::create([
                'text_id' => $text->id,
                'title_fr' => $analysisResult['titleFR'] ?? null,
                'title_ar' => $analysisResult['titleAR'] ?? null,
                'subtitle_fr' => $analysisResult['subtitleFR'] ?? null,
                'subtitle_ar' => $analysisResult['subtitleAR'] ?? null,
                'paragraphs' => $analysisResult['paragraphs'] ?? [],
            ]);

            // Reload text with relations
            $text->load('paragraph');

            return response()->json([
                'success' => true,
                'message' => 'Text analyzed successfully',
                'data' => [
                    'text_id' => $text->id,
                    'user_id' => $text->user_id,
                    'original_text' => $text->original_text,
                    'analysis' => [
                        'title_fr' => $paragraph->title_fr,
                        'title_ar' => $paragraph->title_ar,
                        'subtitle_fr' => $paragraph->subtitle_fr,
                        'subtitle_ar' => $paragraph->subtitle_ar,
                        'paragraphs' => $paragraph->paragraphs,
                        'sentences_count' => is_array($paragraph->paragraphs) && isset($paragraph->paragraphs[0])
                            ? count($paragraph->paragraphs[0])
                            : 0
                    ],
                    'created_at' => $text->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $text->updated_at->format('Y-m-d H:i:s')
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Text Analysis Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while analyzing the text',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get All Texts for authenticated user
     * GET /api/v1/texts
     */
    public function getTexts(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $perPage = min($perPage, 50); // Maximum 50 texts per page

            $texts = Text::with('paragraph')
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate($perPage);

            $transformedTexts = $texts->getCollection()->map(function ($text) {
                return [
                    'text_id' => $text->id,
                    'original_text' => $text->original_text,
                    'title_ar' => $text->paragraph->title_ar ?? null,
                    'title_fr' => $text->paragraph->title_fr ?? null,
                    'sentences_count' => is_array($text->paragraph->paragraphs ?? []) && isset($text->paragraph->paragraphs[0])
                        ? count($text->paragraph->paragraphs[0])
                        : 0,
                    'created_at' => $text->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $text->updated_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Texts retrieved successfully',
                'data' => $transformedTexts,
                'pagination' => [
                    'total' => $texts->total(),
                    'per_page' => $texts->perPage(),
                    'current_page' => $texts->currentPage(),
                    'last_page' => $texts->lastPage(),
                    'from' => $texts->firstItem(),
                    'to' => $texts->lastItem()
                ],
                'user_id' => auth()->id()
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Texts Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve texts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Specific Text with full analysis
     * GET /api/v1/texts/{id}
     */
    public function getText($id)
    {
        try {
            $text = Text::with('paragraph')->findOrFail($id);

            // Check ownership
            if ($text->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to access this text'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Text retrieved successfully',
                'data' => [
                    'text_id' => $text->id,
                    'user_id' => $text->user_id,
                    'original_text' => $text->original_text,
                    'analysis' => [
                        'title_fr' => $text->paragraph->title_fr ?? null,
                        'title_ar' => $text->paragraph->title_ar ?? null,
                        'subtitle_fr' => $text->paragraph->subtitle_fr ?? null,
                        'subtitle_ar' => $text->paragraph->subtitle_ar ?? null,
                        'paragraphs' => $text->paragraph->paragraphs ?? [],
                        'sentences_count' => is_array($text->paragraph->paragraphs ?? []) && isset($text->paragraph->paragraphs[0])
                            ? count($text->paragraph->paragraphs[0])
                            : 0
                    ],
                    'created_at' => $text->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $text->updated_at->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Text not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('API Get Text Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve text',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze text with Gemini AI
     */
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
            throw new \Exception("Failed to connect to the server: " . $response->status());
        }

        $responseData = $response->json();

        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception('Invalid response from Gemini API');
        }

        $analysisText = $responseData['candidates'][0]['content']['parts'][0]['text'];

        // Clean text
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

        $analysisResult = json_decode($analysisText, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Error: ' . json_last_error_msg());
            Log::error('Raw response: ' . $analysisText);
            throw new \Exception('JSON parsing error: ' . json_last_error_msg());
        }

        return $analysisResult;
    }

    /**
     * Generate prompt for Gemini AI
     */
    private function generatePrompt($text)
    {
        return "
You are a linguistic analyst specialized in French and Arabic. Analyze the following French text and return the result in accurate JSON format as specified below.

Required:
1. Provide a short suitable title for the text in French and its Arabic translation
2. Provide a short subtitle in French and its Arabic translation
3. Split the text into logical sentences
4. For each sentence: translate it into Arabic + provide grammatical details for each word

Strict JSON format:
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
          \"[Word1]: [Its grammatical parsing and role]\",
          \"[Word2]: [Its grammatical parsing and role]\",
          \"[Other words with their parsing...]\"
        ]
      },
      {
        \"id\": \"02\",
        \"phraseFR\": \"[Second French sentence]\",
        \"phraseAR\": \"[Arabic translation of the sentence]\",
        \"grammarDetails\": [
          \"[Word1]: [Its grammatical parsing and role]\",
          \"[Other words...]\"
        ]
      }
    ]
  ]
}

French text for analysis:
$text

Make sure to return **only valid JSON**, with no additional text before or after the JSON.
";
    }
}
