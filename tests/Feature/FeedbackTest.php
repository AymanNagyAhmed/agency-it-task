<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;
    /**
     * tests feedback show list with anonymous user
     *
     * @return void
     */
    public function test_show_feedback_no_login()
    {
        $feedback = Feedback::find(1);
        $response = $this->json("GET", "/api/feedbacks/1/");
        $response->assertStatus(401);
    }

    /**
     * tests feedback show list with employee user
     *
     * @return void
     */
    public function test_show_feedback_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/feedbacks/1/");
        $response->assertStatus(403);
    }
    /**
     * tests feedback show list with admin user
     *
     * @return void
     */
    public function test_show_feedback_login()
    {
        $id = 1;
        $feedback = Feedback::find($id);
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/feedbacks/$id/");
        $response->assertStatus(200);
        $response->assertJson(["review_id" => $feedback->review_id]);
    }
    public function test_list_feedbacks_no_login()
    {
        $response = $this->json("GET", "/api/feedbacks");
        $response->assertStatus(401);
    }
    public function test_list_feedbacks_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/feedbacks");
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
    public function test_list_feedback_login()
    {
        $seeder_count = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/feedbacks");
        $response->assertStatus(200);
        $this->assertDatabaseCount("feedbacks", $seeder_count);
        $response->assertJsonCount($seeder_count);
    }

    public function test_update_feedback_no_login()
    {
        $id = 1;
        $feedback_old = Feedback::find($id);
        $response = $this->json("PATCH", "/api/feedbacks/$id", ["body" => "test_feedback"]);
        $response->assertStatus(401);
        $feedback_updated = Feedback::find($id);
        $this->assertEquals($feedback_updated, $feedback_old);
    }
    public function test_update_feedback_employee_login()
    {
        $id = 1;
        $feedback_old = Feedback::find($id);
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("PATCH", "/api/feedbacks/$id", ["body" => "test_feedback"]);
        $response->assertStatus(403);
        $feedback_updated = Feedback::find($id);
        $this->assertEquals($feedback_updated, $feedback_old);
    }
    public function test_update_feedback_login()
    {
        $id = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response =  $this->actingAs($admin)->json("PATCH", "/api/feedbacks/$id", ["body" => "test_feedback"]);
        $response->assertStatus(200);
        $feedback_updated = Feedback::find($id);
        $this->assertEquals($feedback_updated->body, "test_feedback");
    }

    public function test_delete_feedback_no_login()
    {
        $id = 1;
        $response = $this->json("DELETE", "/api/feedbacks/$id");
        $response->assertStatus(401);
        $this->assertDatabaseHas("feedbacks", ["id" => $id]);
    }
    public function test_delete_feedback_login()
    {
        $id = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("DELETE", "/api/feedbacks/$id");
        $response->assertStatus(200);
        $this->assertDatabaseMissing("feedbacks", ["id" => $id]);
    }
    public function test_delete_feedback_emoloyee_login()
    {
        $id = 1;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("DELETE", "/api/feedbacks/$id");
        $response->assertStatus(403);
        $this->assertDatabaseHas("feedbacks", ["id" => $id]);
    }

    public function test_create_feedback_no_login()
    {
        $response = $this->json("POST", "/api/feedbacks", [
            "body" => null,
            'review_id' => 1,
            "reviewer_id" => 4,
        ]);
        $response->assertStatus(401);
    }
    public function test_create_feedback_employee_login()
    {
        $seeder_count = 1;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("POST", "/api/feedbacks", [
            "body" => null,
            'review_id' => 1,
            "reviewer_id" => 4,
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseCount("feedbacks", $seeder_count);
    }
    public function test_create_feedback_login()
    {
        $seeder_count = 1;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("POST", "/api/feedbacks", [
            "body" => null,
            'review_id' => 1,
            "reviewer_id" => 4,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount("feedbacks", $seeder_count + 1);
    }

    protected $seed = true;
}
