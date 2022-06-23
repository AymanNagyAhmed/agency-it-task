<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "review_id"=> 1,
            "reviewer_id"=> 3,
            "body"=> null,
            "created_at"=> now(),
            "updated_at"=> now(),
        ];
    }
}
