<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        if (is_null($users)){
            return response()->json(["users"=>[]]);
        }
        return  response()->json(["users"=>$users]);
    }

    /**
     * create a new user and store to databases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @api
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
            'is_admin' => 'boolean'
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        // Check if validation pass then create user and auth token. Return the auth token
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->is_admin
            ]);
            return response()->json([
                'message' => 'created successfully',
            ]);
        }
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
    public function update(Request $request, User $user)
    {

        $user->update($request->all());

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

        return response()->json(["msg"=>"User Deleted Successfully."]);
    }
}
