<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * tests review show list with anonymous user
     *
     * @return void
     */
    public function test_show_review_no_login()
    {
        $review = Review::find(1);
        $response = $this->json("GET", "/api/reviews/1/");
        $response->assertStatus(401);
    }

    /**
     * tests review show list with employee user
     *
     * @return void
     */
    public function test_show_review_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/reviews/1/");
        $response->assertStatus(403);
    }
    /**
     * tests review show list with admin user
     *
     * @return void
     */
    public function test_show_review_login()
    {
        $id = 1;
        $review = Review::find($id);
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/reviews/$id/");
        $response->assertStatus(200);
        $response->assertJson(["reviewee_id" => $review->reviewee_id]);
    }
    public function test_list_reviews_no_login()
    {
        $response = $this->json("GET", "/api/reviews");
        $response->assertStatus(401);
    }
    public function test_list_reviews_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/reviews");
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
    public function test_list_review_login()
    {
        $seeder_count = 5;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/reviews");
        $response->assertStatus(200);
        $this->assertDatabaseCount("reviews", $seeder_count);
        $response->assertJsonCount($seeder_count);
    }

    public function test_update_review_no_login()
    {
        $id = 1;
        $review_old = Review::find($id);
        $response = $this->json("PATCH", "/api/reviews/$id", ["body" => "test_review"]);
        $response->assertStatus(401);
        $review_updated = Review::find($id);
        $this->assertEquals($review_updated, $review_old);
    }
    public function test_update_review_employee_login()
    {
        $id = 1;
        $review_old = Review::find($id);
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("PATCH", "/api/reviews/$id", ["body" => "test_review"]);
        $response->assertStatus(403);
        $review_updated = Review::find($id);
        $this->assertEquals($review_updated, $review_old);
    }
    public function test_update_review_login()
    {
        $id = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response =  $this->actingAs($admin)->json("PATCH", "/api/reviews/$id", ["body" => "test_review"]);
        $response->assertStatus(200);
        $review_updated = Review::find($id);
        $this->assertEquals($review_updated->body, "test_review");
    }

    public function test_delete_review_no_login()
    {
        $id = 1;
        $response = $this->json("DELETE", "/api/reviews/$id");
        $response->assertStatus(401);
        $this->assertDatabaseHas("reviews", ["id" => $id]);
    }
    public function test_delete_review_login()
    {
        $id = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("DELETE", "/api/reviews/$id");
        $response->assertStatus(200);
        $this->assertDatabaseMissing("reviews", ["id" => $id]);
    }
    public function test_delete_review_emoloyee_login()
    {
        $id = 1;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("DELETE", "/api/reviews/$id");
        $response->assertStatus(403);
        $this->assertDatabaseHas("reviews", ["id" => $id]);
    }

    public function test_create_review_no_login()
    {
        $response = $this->json("POST", "/api/reviews", [
            "body" => "test test",
            'reviewee_id' => 1,
        ]);
        $response->assertStatus(401);
    }
    public function test_create_review_employee_login()
    {
        $seeder_count = 5;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("POST", "/api/reviews", [
            "body" => "test test",
            'reviewee_id' => 1,
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseCount("reviews", $seeder_count);
    }
    public function test_create_review_login()
    {
        $seeder_count = 5;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("POST", "/api/reviews", [
            "body" => "test test",
            'reviewee_id' => 3,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount("reviews", $seeder_count + 1);
    }

    protected $seed = true;

}
