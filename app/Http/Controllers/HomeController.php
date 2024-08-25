<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $req)
    {
        $query = Book::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('deleted', 0)
            ->where('status', 1)
            ->where('image', '!=', null)
            ->orderBy('created_at', 'DESC');
        if (!empty($req->keyword)) {
            $query->where('title', 'like', '%' . $req->keyword . '%');
        }
        $books = $query->paginate(8);
        return view('home', ['books' => $books]);
    }
    public function detail($id)
    {
        $book = Book::withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->find($id);
        if ($book->status == 0) {
            abort(404);
        }
        $bookreview = Review::where('status', '=', 1)->where('book_id', $id)->with('user')->get();
        $relatedBooks = Book::where('deleted', 0)->where('status', 1)->where('image', '!=', null)->take(3)->inRandomOrder()->get();
        return view('book-detail', ['book' => $book, 'relatedBooks' => $relatedBooks, 'bookreview' => $bookreview]);
    }
}
