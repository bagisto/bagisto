<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_type', // image, video, document, audio
        'mime_type',
        'file_size', // in bytes
        'disk', // public, s3, etc.
        'folder',
        'alt_text',
        'title',
        'description',
        'width',
        'height',
        'is_public',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_public' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->where('file_type', 'document');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeInFolder($query, string $folder)
    {
        return $query->where('folder', $folder);
    }

    // Accessors
    public function getUrlAttribute()
    {
        if ($this->disk === 's3') {
            return \Storage::disk('s3')->url($this->file_path);
        }

        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->file_type !== 'image') {
            return null;
        }

        // Implement thumbnail generation logic
        $pathInfo = pathinfo($this->file_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];

        if (\Storage::disk($this->disk)->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }

        return $this->url;
    }

    // Methods
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isDocument(): bool
    {
        return $this->file_type === 'document';
    }

    public function delete(): ?bool
    {
        // Delete physical file
        if (\Storage::disk($this->disk)->exists($this->file_path)) {
            \Storage::disk($this->disk)->delete($this->file_path);
        }

        // Delete thumbnail if exists
        if ($this->isImage()) {
            $pathInfo = pathinfo($this->file_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];
            
            if (\Storage::disk($this->disk)->exists($thumbnailPath)) {
                \Storage::disk($this->disk)->delete($thumbnailPath);
            }
        }

        return parent::delete();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            // Detect file type from mime type
            if (empty($media->file_type)) {
                $media->file_type = static::detectFileType($media->mime_type);
            }
        });
    }

    private static function detectFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }

        return 'document';
    }
}
