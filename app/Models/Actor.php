<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    /** @use HasFactory<\Database\Factories\ActorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'birthdate',
        'gender',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'swapi_url',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
