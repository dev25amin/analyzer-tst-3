<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paragraph extends Model
{
    use HasFactory;

    protected $table = 'paragraphs';

    protected $fillable = [
        'text_id',
        'title_fr',
        'title_ar',
        'subtitle_fr',
        'subtitle_ar',
        'paragraphs',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع النص الأصلي
     */
    public function text()
    {
        return $this->belongsTo(Text::class);
    }

    /**
     * حفظ paragraphs بشكل واضح (بدون Unicode Escaping)
     */
    public function setParagraphsAttribute($value)
    {
        $this->attributes['paragraphs'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * إرجاع paragraphs كمصفوفة جاهزة للاستعمال
     */
    public function getParagraphsAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * ترتيب الجمل بشكل منسق
     */
    public function getFormattedParagraphs()
    {
        $formatted = [];
        $paragraphs = $this->paragraphs ?? [];

        foreach ($paragraphs as $section) {
            foreach ($section as $sentence) {
                $formatted[] = [
                    'id' => $sentence['id'] ?? '',
                    'phraseFR' => $sentence['phraseFR'] ?? '',
                    'phraseAR' => $sentence['phraseAR'] ?? '',
                    'grammarDetails' => $sentence['grammarDetails'] ?? []
                ];
            }
        }

        return $formatted;
    }
}
