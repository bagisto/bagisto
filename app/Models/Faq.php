<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question',
        'question_en',
        'answer',
        'answer_en',
        'sort_order',
        'is_featured',
        'status',
        'view_count',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopePopular($query, int $limit = 10)
    {
        return $query->orderBy('view_count', 'desc')->limit($limit);
    }

    // Methods
    public function incrementView(): void
    {
        $this->increment('view_count');
    }

    public static function getCategories(): array
    {
        return static::active()
            ->distinct()
            ->pluck('category')
            ->toArray();
    }
}
