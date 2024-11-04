<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordMail;
use App\Models\User;
use App\Models\Book;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Pail\ValueObjects\Origin\Console;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{



    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomPassword = $this->generateRandomPassword(8);

        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'email' => $request->input('email'),
            'date_of_birth' => $request->input('date_of_birth'),
            'password' => Hash::make($randomPassword),
        ]);

        Mail::to($user->email)->send(new \App\Mail\SendPasswordMail($randomPassword));

        $token = JWTAuth::fromUser($user);
        return redirect()->route('login');
    }



    private function generateRandomPassword($length = 8)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $caps = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#%^&*()_';

        $allCharacters = $letters . $numbers . $caps . $specialChars;

        return substr(str_shuffle(str_repeat($allCharacters, $length)), 0, $length);
    }




    public function login(Request $request)
    {
        $request->validate(config('validation.login'));



        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'password' => 'The provided credentials do not match our records.',
            ]);
        }


        $user = JWTAuth::user();

        Auth::login($user);

        session(['auth_token' => $token]);

        if (!$user->password_changed) {
            return redirect()->route('change_password.form');
        }
        if ($user) {
            return redirect('booksIndex');
        }

        return redirect()->route('welcome');
    }



    public function showChangePasswordForm()
    {

        $token = session('auth_token');


        if (!$token) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in to access this page.']);
        }

        try {

            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return 'user not found';
            }
        } catch (JWTException $e) {
            return 'token not found';
        }

        return view('auth.change_password');
    }








    public function changePassword(Request $request)
    {

        $token = session('auth_token');


        if (!$token) {
            return redirect()->route('login');
        }

        try {

            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return  'Unauthorized access';
            }
        } catch (JWTException $e) {
            return 'Token not found';
        }


        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:5|confirmed',
        ]);




        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
        }



        $user->password = Hash::make($request->new_password);
        $user->password_changed = True;
        $user->save();

        Auth::login($user);

        return redirect()->route('booksCreate');
    }

    public function search(Request $request)
    {
        $request->validate(['query' => 'required|string']);
        $query = $request->input('query');
    
        $books = Book::where('title', 'LIKE', "%{$query}%")
                    ->select('id', 'title', 'author', 'publish_date', 'description', 'user_id')
                    ->get();
    
        return response()->json($books);
    }
    



    public function create()
    {
        return view('auth.createBook');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'required|date'
        ]);


        Auth::user()->books()->create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'description' => $request->input('description'),
            'publish_date' => $request->input('publish_date'),
        ]);
        return redirect()->route('booksIndex')->with('success', 'Book created successfully.');
    }

    public function index()
    {
        $books = Auth::user()->books()->paginate(5);
        return view('auth.index', compact('books'));
    }


    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('auth.edit', compact('book'));
    }


    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'required|date'
        ]);

        $book = Auth::user()->books()->findOrFail($id);

        $book->update([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'description' => $request->input('description'),
            'publish_date' => $request->input('publish_date')
        ]);

        return redirect()->route('booksIndex')->with('success', 'Book updated successfully.');
    }


    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->user_id == Auth::id()) {
            $book->delete();
            return redirect()->route('booksIndex')->with('success', 'Book deleted successfully.');
        }

        return redirect()->route('booksIndex')->with('error', 'You are not authorized to delete this book.');
    }



    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->input('email'))->first();


        $randomPassword = $this->generateRandomPassword(5);
        Mail::to($user->email)->send(new \App\Mail\SendPasswordMail($randomPassword));


        $user->password = Hash::make($randomPassword);
        $user->password_changed = true;
        $user->save();


        return redirect()->route('change_password.form');
    }




    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }



    public function logout(Request $request)
    {

        Auth::logout();


        return redirect()->route('login');
    }
}
