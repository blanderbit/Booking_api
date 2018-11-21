<?php

namespace App;
use App\Comment;
use App\Rent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class Post extends Model
{
    protected $fillable = [
        'user_id', 'office_name', 'about_office','address'
    ];

//    protected $appends = ['count_rent','count_comment'];

    protected $withCount = ['rent','comment'];

//    public function getCountRentAttribute()
//    {
//        return $this->rent()->count();
//    }
//
//
//    public function getCountCommentAttribute()
//    {
//        return $this->comment()->count();
//    }

    public  function rent()
    {
        return $this->hasMany(Rent::class);
    }
    public  function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
