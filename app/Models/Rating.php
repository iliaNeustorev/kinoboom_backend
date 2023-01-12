<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [ 'user_id','appreciated' ];

    public function ratingable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserAppreciated()
    {
        return function ($query)
        {
            $query->where('user_id', auth()->id())->where('appreciated', true);
        };
    }
}
