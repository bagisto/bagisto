<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_name', // default, auth, product, order, etc.
        'description',
        'subject_type',
        'subject_id',
        'event', // created, updated, deleted, viewed, etc.
        'properties', // JSON for old and new values
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLogName($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    public function scopeForSubject($query, string $subjectType, int $subjectId)
    {
        return $query->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public static function log(
        string $description,
        ?string $logName = 'default',
        ?string $event = null,
        $subject = null,
        ?array $properties = null
    ): self {
        $log = new static([
            'user_id' => auth()->id(),
            'log_name' => $logName,
            'description' => $description,
            'event' => $event,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($subject) {
            $log->subject_type = get_class($subject);
            $log->subject_id = $subject->id;
        }

        $log->save();

        return $log;
    }

    public static function logModelEvent($model, string $event, ?array $oldValues = null): self
    {
        $properties = [
            'attributes' => $model->getAttributes(),
        ];

        if ($oldValues) {
            $properties['old'] = $oldValues;
        }

        return static::log(
            description: class_basename($model) . " {$event}",
            logName: strtolower(class_basename($model)),
            event: $event,
            subject: $model,
            properties: $properties
        );
    }

    public function getChangesAttribute(): array
    {
        if (!isset($this->properties['old']) || !isset($this->properties['attributes'])) {
            return [];
        }

        $old = $this->properties['old'];
        $new = $this->properties['attributes'];
        $changes = [];

        foreach ($new as $key => $value) {
            if (isset($old[$key]) && $old[$key] !== $value) {
                $changes[$key] = [
                    'old' => $old[$key],
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    public static function cleanup(int $days = 90): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
