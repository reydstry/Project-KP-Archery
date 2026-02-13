<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'photo_path',
        'title',
        'content',
        'publish_date',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }
        return asset('storage/' . $this->photo_path);
    }

    public function scopePublished($query)
    {
        return $query->whereDate('publish_date', '<=', now()->toDateString());
    }
}
