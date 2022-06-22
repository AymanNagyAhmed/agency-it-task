<?php

namespace App\Models;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'body'
    ];

    public function review(){
        return $this->belongsTo(Review::class);
    }
}
