<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'member_id',
        'title',
        'description',
        'date',
        'photo_path',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }
        return asset('storage/' . $this->photo_path);
    }

    /**
     * Get the member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopePublished($query)
    {
        return $query->whereDate('date', '<=', now()->toDateString());
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for recent achievements
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('date', 'desc')->limit($limit);
    }
}
