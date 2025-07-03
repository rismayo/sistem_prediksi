<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            return redirect()->intended('superadmin');
        }
        return view('login');
    }

    public function proses_login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $credential = $request->only('username', 'password');
        if (Auth::attempt($credential)) {
            $user = Auth::user();
            if ($user->level == 'superadmin') {
                return redirect()->route('dashboard');
            } elseif ($user->level == 'admin') {
                return redirect()->route('dashboard');
            }
        }
        return redirect('/')
            ->withInput()
            ->withErrors(['login_gagal' => 'username atau password yang anda masukan salah']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return Redirect()->route('login');
    }
}