<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subject',
        'subject_en',
        'body',
        'body_en',
        'variables', // JSON array of available variables
        'category', // transactional, marketing, system
        'is_active',
        'is_system', // System templates cannot be deleted
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    // Methods
    public function render(array $data = [], string $locale = 'th'): array
    {
        $subject = $locale === 'en' && $this->subject_en 
            ? $this->subject_en 
            : $this->subject;

        $body = $locale === 'en' && $this->body_en 
            ? $this->body_en 
            : $this->body;

        // Replace variables
        foreach ($data as $key => $value) {
            $subject = str_replace("{{" . $key . "}}", $value, $subject);
            $body = str_replace("{{" . $key . "}}", $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    public function clone(string $newName): self
    {
        $clone = $this->replicate();
        $clone->name = $newName;
        $clone->slug = \Illuminate\Support\Str::slug($newName);
        $clone->is_system = false;
        $clone->save();

        return $clone;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = \Illuminate\Support\Str::slug($template->name);
            }
        });

        static::deleting(function ($template) {
            if ($template->is_system) {
                throw new \Exception('ไม่สามารถลบเทมเพลตระบบได้');
            }
        });
    }
}
