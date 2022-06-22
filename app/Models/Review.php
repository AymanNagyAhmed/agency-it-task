<?php

namespace App\Models;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
    ];

    public function reviewer(){
        return $this->belongsTo(User::class);
    }
    public function reviewee(){
        return $this->belongsTo(User::class);
    }
    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }
}
