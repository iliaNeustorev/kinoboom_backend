<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Serial extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'video',
        'picture',
        'rating',
        'year_release'
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }
}
