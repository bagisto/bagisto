<?php

namespace Frosko\FairySync\Models\Z1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    protected $connection = 'z1';

    use HasFactory;

    protected $fillable = [
        'product_id',
        'path',
        'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): string
    {
        return Storage::drive('photos')->url($this->path);
    }

    public function copyToOc(): string
    {
        $filepath = Storage::path($this->path);
        if (! File::exists($filepath)) {
            throw new \RuntimeException("File {$filepath} not found");
        }

        $hash = File::hash($filepath);

        $newFilename = Str::of($hash)
            ->prepend('photos/'.Str::substr($hash, 0, 2).'/')
            ->append('.'.File::extension($filepath))
            ->lower();

        if (! Storage::disk('oc-img')->exists($newFilename)) {
            File::ensureDirectoryExists(Storage::disk('oc-img')->path(Str::before($newFilename, '/')));

            Storage::disk('oc-img')->put(
                $newFilename,
                Storage::get($this->path), [
                    'visibility' => 'public',
                ]);
        }

        return Storage::disk('oc-img')->exists($newFilename) ? $newFilename : '';
    }
}
