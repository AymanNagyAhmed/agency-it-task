<?php

namespace App\Http\Controllers;
use App\Http\Requests\ReviewRequest\ShowReviewRequest;
use App\Http\Requests\ReviewRequest\StoreReviewRequest;
use App\Http\Requests\ReviewRequest\UpdateReviewRequest;
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
        if (Auth::user()->is_admin) {
            return Review::all();
        } else {
            return Review::whereHas("feedbacks", function ($feedbacks) {
                $feedbacks->where("reviewer_id", Auth::user()->id);
            })->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReviewRequest $request)
    {
        $validated = $request->validated();
        Review::create([
            ...$validated,
            "reviewer_id" => Auth::user()->id
        ]);
        return response()->json([
            'message' => 'created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Review $review
     * @return \Illuminate\Http\Response
     */
    public function show(ShowReviewRequest $request, Review $review)
    {
        return $review;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $validated = $request->validated();
        $review->update($validated);
        return response()->json([
            'message' => 'updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(["message" => "review Deleted Successfully."]);
    }
}
