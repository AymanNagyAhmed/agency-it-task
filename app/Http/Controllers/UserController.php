<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     * @api
     */
    public function index()
    {
        $users = User::all();
        if (is_null($users)) {
            return response()->json(["users" => []]);
        }
        return  response()->json(["users" => $users]);
    }

    /**
     * create a new user and store to databases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @api
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated["password"] = Hash::make($validated["password"]);
        $user = User::create($validated);
        return response()->json([
            'message' => 'created successfully',
        'user'=>$user]);
    }

    /**
     * Display the specified user.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response user in json format
     * @api
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified user in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user User to be updated
     * @return \Illuminate\Http\Response
     * @api
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if (array_key_exists("password",$validated)) {
            $validated["password"] = Hash::make($validated["password"]);
        }
        $user->update($validated);
        return $user;
    }

    /**
     * Remove the specified user from database.
     *
     * @param  User  $user User to be deleted
     * @return \Illuminate\Http\Response
     * @api
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(["message" => "User Deleted Successfully."]);
    }
}
