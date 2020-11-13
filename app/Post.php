<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['age','photo','open','allowemail1','allowemail2'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
