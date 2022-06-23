<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * tests user show list with anonymous user
     *
     * @return void
     */
    public function test_show_user_no_login()
    {
        $user = User::find(1);
        $response = $this->json("GET", "/api/admin/user/1/");
        $response->assertStatus(401);
    }
     /**
     * tests user show list with employee user
     *
     * @return void
     */
    public function test_show_user_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/admin/user/1/");
        $response->assertStatus(403);
    }
    /**
     * tests user show list with admin user
     *
     * @return void
     */
    public function test_show_user_login()
    {
        $id = 3;
        $user = User::find($id);
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/admin/user/$id/");
        $response->assertStatus(200);
        $response->assertJson(["name" => $user->name]);
    }
    public function test_list_users_no_login()
    {
        $response = $this->json("GET", "/api/admin/users-list");
        $response->assertStatus(401);
    }
    public function test_list_users_employee_login()
    {
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("GET", "/api/admin/users-list");
        $response->assertStatus(403);
    }
    public function test_list_user_login()
    {
        $seeder_count = 12;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("GET", "/api/admin/users-list");
        $response->assertStatus(200);
        $this->assertDatabaseCount("users", $seeder_count + 1);
        $response->assertJsonCount($seeder_count + 1, "users");
    }


    public function test_update_user_no_login()
    {
        $id = 3;
        $user_old = User::find($id);
        $response = $this->json("PATCH", "/api/admin/user/$id", ["name" => "test_user"]);
        $response->assertStatus(401);
        $user_updated = User::find($id);
        $this->assertEquals($user_updated, $user_old);
    }
    public function test_update_user_employee_login()
    {
        $id = 3;
        $user_old = User::find($id);
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("PATCH", "/api/admin/user/$id", ["name" => "test_user"]);
        $response->assertStatus(403);
        $user_updated = User::find($id);
        $this->assertEquals($user_updated, $user_old);
    }
    public function test_update_user_login()
    {
        $id = 3;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response =  $this->actingAs($admin)->json("PATCH", "/api/admin/user/$id", ["name" => "test_user"]);
        $response->assertStatus(200);
        $user_updated = User::find($id);
        $this->assertEquals($user_updated->name, "test_user");
    }

    public function test_delete_user_no_login()
    {
        $id = 3;
        $response = $this->json("DELETE", "/api/admin/user/$id");
        $response->assertStatus(401);
        $this->assertDatabaseHas("users", ["id" => $id]);
    }
    public function test_delete_user_login()
    {
        $id = 3;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("DELETE", "/api/admin/user/$id");
        $response->assertStatus(200);
        $this->assertDatabaseMissing("users", ["id" => $id]);
    }
    public function test_delete_user_emoloyee_login()
    {
        $id = 3;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("DELETE", "/api/admin/user/$id");
        $response->assertStatus(403);
        $this->assertDatabaseHas("users", ["id" => $id]);
    }

    public function test_create_user_no_login()
    {
        $response = $this->json("POST", "/api/admin/user", [
            "name" => "test_user",
            'email' => "test@test.com",
            "password" => "123456",
            "is_admin" => false
        ]);
        $response->assertStatus(401);
        // $this->assertDatabaseHas("users",[] );
    }
    public function test_create_user_employee_login()
    {
        $seeder_count = 12;
        $non_admin = User::factory()->create(["name" => "user_test", "is_admin" => false]);
        $response = $this->actingAs($non_admin)->json("POST", "/api/admin/user", [
            "name" => "test_user",
            'email' => "test@test.com",
            "password" => "123456",
            "is_admin" => false
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseCount("users", $seeder_count + 1);

    }
    public function test_create_user_login()
    {
        $seeder_count = 12;
        $admin = User::factory()->create(["name" => "admin_test", "is_admin" => true]);
        $response = $this->actingAs($admin)->json("POST", "/api/admin/user", [
            "name" => "test_user",
            'email' => "test@test.com",
            "password" => "12345671",
            "is_admin" => false
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount("users", $seeder_count +2);
    }

    protected $seed = true;
}
