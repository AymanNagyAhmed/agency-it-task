<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => "test review is required",
            "reviewer_id"=> 12,
            "reviewee_id"=>2,
        ];
    }
}
