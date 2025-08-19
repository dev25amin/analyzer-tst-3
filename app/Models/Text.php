<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;

    protected $table = 'texts';

    protected $fillable = [
        'user_id',
        'original_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [];
    protected $encrypted = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paragraph()
    {
        return $this->hasOne(Paragraph::class);
    }
}
