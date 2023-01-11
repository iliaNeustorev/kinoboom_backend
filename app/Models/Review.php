<?php

namespace App\Models;

use App\Enums\Review\Status;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Like\Status as LikeStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    public $fillable = [
        'review',
        'user_id',
    ];

    protected $casts = [
        'status' => Status::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function scopeGetNew()
    {
        return $this->where('status', Status::NEW)->with('user:id,name');
    }

    public function scopeGetAccept()
    {
        return $this->where('status', Status::ACCEPT)
            ->with('user:id,name')
            ->withCount([
                'likes as likes_count' => function (Builder $query) {
                    $query->where('status', LikeStatus::LIKE);
                    },
                'likes as dislikes_count' => function (Builder $query) {
                    $query->where('status', LikeStatus::DISLIKE);
                    }
                ]);
    }
}


