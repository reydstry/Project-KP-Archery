<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'session_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:0',
            'duration_days' => 'integer',
            'session_count' => 'integer',
        ];
    }

    public function memberPackages()
    {
        return $this->hasMany(MemberPackage::class);
    }
}
