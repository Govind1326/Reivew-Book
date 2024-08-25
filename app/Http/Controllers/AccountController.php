<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function register()
    {
        return view("account.register");
    }
    public function newUser(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required | min:3',
            'email' => 'required | email',
            'password' => 'required | confirmed | min:5',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
        $existingUser = User::where('email', $req->email)->first();
        if ($existingUser) {
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors(['email' => 'The email is already registered.']);
        }
        //adding user
        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->save();
        return redirect()->route('account.login')->with('success', 'You have registered successfully.');
    }
    public function login()
    {
        return view("account.login");
    }
    public function authenticate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        $user = User::where('email', $req->email)->first();
        if (!$user) {
            session()->flash('error','You are not registered yet.');
            return redirect()->route('account.login')
                ->withInput()
                ->withErrors(['email' => 'User not found.']);
        } elseif ($user) {
            if (!Hash::check($req->password, $user->password)) {
                session()->flash('error','Please entered correct password.');
                return redirect()->route('account.login')->withInput()->withErrors(['password' => 'Invalid password.']);
            } elseif (Auth::attempt(['email' => $req->email, 'password' => $req->password, 'deleted'=>0])) {
                $user->last_login = now();
                $user->save();
                return redirect()->route('account.profile')->with('success', 'You have logged-in successfully.');
            }
            session()->flash('error','You are not authorized to login or you are banned from website.');
            return redirect()->route('account.login')->withInput()->withErrors(['password' => 'Invalid password.']);

        }
    }
    public function profile()
    {
        $user = User::withCount('reviews')->find(Auth::user()->id);
        return view('account.profile', ['user' => $user]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }
    public function updateProfile(Request $req)
    {
        $rules = [
            'name' => 'required | min:3',
            'email' => 'required | email | unique:users,email,' . Auth::user()->id . ',id',
        ];
        if (!empty($req->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $req->name;
        $user->email = $req->email;
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $imageName);
            $user->image = 'uploads/profile/' . $imageName;
        }
        $user->save();

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }
    public function passwordPage(){
        return view('account.password-page');
    }
    public function changepassword(Request $req)
    {
        $rules = [
            'password_old' => 'required',
            'changepassword' => 'required | min:5',
        ];
        
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('account.changePassword')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        if (!Hash::check($req->password_old, $user->password)) {
            return redirect()->route('account.changePassword')->withInput()->withErrors(['password_old' => 'Old password do not match.']);
        }
        if (Hash::check($req->changepassword, $user->password)) {
            session()->flash('error', 'Your password is not changed.');
            return redirect()->route('account.changePassword')->withInput()->withErrors(['changepassword' => 'New password is same as old password.']);
        }
        $user->password = Hash::make($req->changepassword);
        $user->save();

        return redirect()->route('account.changePassword')->with('success', 'Password change successfully.');
    }
    public function myreviews()
    {
        $allReviews = Review::with(['user', 'book']) // Eager load user and book relationships
            ->where('deleted', '=', 0)
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        return view('account.my-reviews', ['allreviews' => $allReviews]);
    }
    public function users()
    {
        $allusers = User::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.users', ['allusers' => $allusers]);
    }
    public function update(Request $req)
    {
        if(Auth::user()->id==$req->id){
            session()->flash('error', 'You cannot update your self.');
            return response()->json(['error' => 'User not updated.'], 404);
        }
        $user = User::find($req->id);
        if (!$user) {
            session()->flash('error', 'User not updated.');
            return response()->json(['error' => 'User not found.'], 404);
        }
        $user->role = $req->role;
        $user->deleted = $req->deleted;
        $user->save();
        session()->flash('success','User updated successfully.');
        return response()->json(['success' => 'User updated successfully.'], 200);
    }   
    public function delete(Request $req)
    {
        if(Auth::user()->id==$req->id){
            session()->flash('warning', 'Warning : You cannot delete your self.');
            return response()->json(['warning' => 'User not deleted.'], 404);
        }
        $user = User::find($req->id);
        if (!$user) {
            session()->flash('error', 'User not deleted.');
            return response()->json(['error' => 'User not deleted.'], 404);
        }
        if($user->deleted==1){
            session()->flash('warning', 'Warning : User is already deleted.');
            return response()->json(['warning' => 'User not deleted.'], 404);
        }
        $user->deleted = 1;
        $user->save();
        session()->flash('success', 'User deleted successfully.');
        return response()->json(['success' => 'User deleted successfully.'], 200);
    }  
}
