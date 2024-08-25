<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::withAvg('reviews', 'rating')->where('deleted', '0')->orderBy('created_at', 'DESC')->paginate(10);
        $user = User::find(Auth::user()->id);
        return view('books.list', ['user' => $user, 'books' => $books]);
    }
    public function create()
    {
        $user = User::find(Auth::user()->id);
        return view('books.create', ['user' => $user]);
    }
    public function store(Request $req)
    {
        $rules = [
            'title' => 'required | min:5',
            'author' => 'required | min:5',
            'status' => 'required'
        ];
        if (!empty($req->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }
        if (!$req->id) {
            $book = new Book();
        } else {
            $book = Book::find($req->id);
            if (!$book) {
                return redirect()->route('books.index')->with('error', 'Book not found.');
            }
        }
        $book->title = $req->title;
        $book->author = $req->author;
        $book->description = $req->description;
        $book->status = $req->status;
        if ($req->hasFile('image')) {
            // Optionally, delete the old image
            if (!empty($book->image) && file_exists(public_path($book->image))) {
                unlink(public_path($book->image));
            }
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $imageName);
            $book->image = 'uploads/profile/' . $imageName;
        }
        $book->save();
        return redirect()->route('books.index')->with('success', $req->id ? 'Book updated successfully.' : 'You have added a book successfully.');
    }
    public function update($id)
    {
        $user = User::find(Auth::user()->id);
        $book = Book::find($id);
        return view('books.create', ['user' => $user, 'book' => $book]);
    }
    public function destroy(Request $req)
    {
        $book = Book::find($req->id);
        if (!$book) {
            session()->flash('error', 'Book not found.');
            return response()->json(['error' => 'Book not found.'], 404);
        }
        $book->deleted = 1;
        $book->save();
        session()->flash('success', 'Book deleted successfully.');
        return response()->json(['success' => 'Book deleted successfully.'], 200);
    }
    public function saveReview(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'review' => 'required | min:5',
            'rating' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false,'error' => $validator->errors()]);
        }
        $countReview=Review::where('user_id',Auth::user()->id)->where('book_id',$req->book_id)->count();
        if($countReview>0){
            session()->flash('error','Review already submitted.');
            return response()->json(['success'=>'Review already submitted.'],200);    
        }
        $bookReview=new Review();
        $bookReview->user_id = Auth::user()->id;
        $bookReview->book_id = $req->book_id;
        $bookReview->review = $req->review;
        $bookReview->rating = $req->rating;
        $bookReview->save();
        session()->flash('success','Review added successfully.');
        return response()->json(['success'=>'Review added successfully.'],200);
    }
}
