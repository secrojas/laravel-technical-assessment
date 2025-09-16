<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    /** @use HasFactory<\Database\Factories\ActorFactory> */
    use HasFactory;

    protected $fillable = ['name', 'birthdate'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
