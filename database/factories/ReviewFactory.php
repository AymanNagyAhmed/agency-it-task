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
        $min = 2;
        $number_of_users_in_seed = 11;
        $admin_id = 12;
        return [
            'body' => "test review is required",
            "reviewer_id"=> $admin_id,
            "reviewee_id"=>rand( $min, $number_of_users_in_seed),
        ];
    }
}
