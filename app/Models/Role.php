<?php

namespace App\Models;

use App\Enums\Roles\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $casts = [
        'name' => Status::class,
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
