<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(){
        $allReviews = Review::with(['user', 'book']) // Eager load user and book relationships
                        ->where('deleted','=',0)
                        ->orderBy('created_at', 'asc')
                        ->paginate(10);
        return view('account.reviews.list',['allreviews'=>$allReviews]);
    }
    public function update(Request $req)
    {
        $review = Review::find($req->id);
        if (!$review) {
            session()->flash('error', 'Review not updated.');
            return response()->json(['error' => 'Review not found.'], 404);
        }
        if (Auth::user()->role!='admin') {
            session()->flash('error', 'You cant update the status of your review.');
            return response()->json(['error' => 'Review not found.'], 404);
        }
        $review->status = $req->status;
        $review->save();
        session()->flash('success','Review updated successfully.');
        return response()->json(['success' => 'Review updated successfully.'], 200);
    }   
    public function destroy(Request $req)
    {
        $review = Review::find($req->id);
        if (!$review) {
            session()->flash('error', 'Review not deleted.');
            return response()->json(['error' => 'Review not deleted.'], 404);
        }
        $review->deleted = 1;
        $review->save();
        session()->flash('success', 'Review deleted successfully.');
        return response()->json(['success' => 'Review deleted successfully.'], 200);
    }   
}
