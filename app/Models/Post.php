<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'body', 'image', 'user_id', 'location', 'username'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likers()
    {
        return $this->belongsToMany(User::class, 'like_user_post')->withTimestamps();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
