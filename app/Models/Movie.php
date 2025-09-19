<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'episode_id',
        'opening_crawl',
        'director',
        'producer',
        'release_date',
        'url',
    ];

    public function actors()
    {
        return $this->belongsToMany(Actor::class)->withTimestamps();
    }
}
