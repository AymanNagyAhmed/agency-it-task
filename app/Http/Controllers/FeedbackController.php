<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the feedbacks.
     *
     * @return \Illuminate\Http\Response
     * @api
     */
    public function index()
    {
        if (Auth::user()->is_admin){
            return Feedback::all();
        } else {
            return Feedback::where("reviewer_id",Auth::user()->id)->get();
        }
    }

    /**
     * create a new feedback and store it to databases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @api
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'body' => 'nullable',
            'review_id' => 'required|integer',
            'reviewer_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            Feedback::create([
                "body"=> $request->body,
                "review_id"=>$request->review_id,
                "reviewer_id"=> $request->reviewer_id
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
        $feedback =  Feedback::find($id);
        if ($request->user()->cannot('view', $feedback)) {
            abort(403);
        }
        return Feedback::find($id);
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
        $feedback =  Feedback::find($id);
        if ($request->user()->cannot('update', $feedback)) {
            abort(403);
        }
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
            $feedback->update([
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
        $feedback=Feedback::find($id);
        $feedback->delete();
        return response()->json(["msg"=>"feedback Deleted Successfully."]);
    }
}
