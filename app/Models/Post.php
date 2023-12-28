<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // A post belongsTo a ((user))
    public function user() { // Laravel makes it available as a property under the hood
        return $this->belongsTo(User::class);
    }
    
    // A post belongsTo a ((category))
    public function category() { // Laravel makes it available as a property under the hood
        return $this->belongsTo(Category::class);
    }
}
