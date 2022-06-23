<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest\ShowFeedbackRequest;
use App\Http\Requests\FeedbackRequest\StoreFeedbackRequest;
use App\Http\Requests\FeedbackRequest\UpdateFeedbackRequest;
use App\Models\Feedback;
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
        if (Auth::user()->is_admin) {
            return Feedback::all();
        } else {
            return Feedback::where("reviewer_id", Auth::user()->id)->get();
        }
    }

    /**
     * create a new feedback and store it to databases.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @api
     */
    public function store(StoreFeedbackRequest $request)
    {
        $validated = $request->validated();
        Feedback::create($validated);
        return response()->json([
            'message' => 'created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Feedback $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(ShowFeedbackRequest $request, Feedback $feedback)
    {
        return $feedback;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Feedback $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeedbackRequest $request, Feedback $feedback)
    {
        $validated = $request->validated();
            $feedback->update($validated);
            return response()->json([
                'message' => 'updated successfully',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Feedback $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response()->json(["msg" => "feedback Deleted Successfully."]);
    }
}
