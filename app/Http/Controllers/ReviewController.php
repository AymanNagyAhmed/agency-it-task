<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     *
     * @return \Illuminate\Http\Response
     * @api
     */
    public function index()
    {
        if (Auth::user()->is_admin){
            return Review::all();
        } else {
            return Review::whereHas("feedbacks",function($feedbacks) {
                $feedbacks->where("reviewer_id",Auth::user()->id);
            })->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'reviewee_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            Review::create([
                "body"=> $request->body,
                "reviewee_id"=> $request->reviewee_id,
                "reviewer_id" => Auth::user()->id
            ]);
            return response()->json([
                'message' => 'created successfully',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $review =  Review::find($id);
        if ($request->user()->cannot('view', $review)) {
            abort(403);
        }
        return Review::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            $review->update([
                "body"=> $request->body
            ]);
            return response()->json([
                'message' => 'updated successfully',
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review=Review::find($id);
        $review->delete();
        return response()->json(["message"=>"review Deleted Successfully."]);
    }
}
