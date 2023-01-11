<?php

namespace App\Models;

use App\Enums\Comment\Status;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Like\Status as LikeStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'text','user_id','status' ];

    protected $casts = [
        'status' => Status::class
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGetWithStatusForAdmin($query, $status)
    {
        return $this->where('status', $status)->with('user:id,name','commentable:id,name,slug');
    }

    public function scopeGetWithStatus($query, $param)
    {
        return function ($query) use ($param) {
            $query->where('status', $param)
                ->with('user:id,name')
                ->withCount(
                    ['likes as likes_count' => function (Builder $query) {
                        $query->where('status', LikeStatus::LIKE);
                    },'likes as dislikes_count' => function (Builder $query) {
                        $query->where('status', LikeStatus::DISLIKE);
                    }]);
        };
    }

    
}
