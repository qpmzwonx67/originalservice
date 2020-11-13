<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = ['nickname','gender','birthday'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }  
}
