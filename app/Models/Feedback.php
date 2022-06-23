<?php

namespace App\Models;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table= "feedbacks";
    protected $fillable = [
        'body',
        'review_id',
        'reviewer_id'
    ];

    public function review(){
        return $this->belongsTo(Review::class);
    }
    public function reviewer(){
        return $this->belongsTo(User::class);
    }
}
