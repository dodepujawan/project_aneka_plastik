<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('home');
        }else{
            return view('login.login');
        }
    }

    // public function actionlogin(Request $request)
    // {
    //     $data = [
    //         'email' => $request->input('email'),
    //         'password' => $request->input('password'),
    //     ];

    //     if (Auth::Attempt($data)) {
    //         session(['id' => auth()->user()->id]);
    //         session(['name' => auth()->user()->name]);
    //         session(['roles' => auth()->user()->roles]);
    //         session(['user_id' => auth()->user()->user_id]);
    //         return redirect('home');
    //     }else{
    //         Session::flash('error', 'Email atau Password Salah');
    //         return redirect('/');
    //     }
    // }

    public function actionlogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Simpan session
            $user = Auth::user();
            session([
                'id' => $user->id,
                'name' => $user->name,
                'roles' => $user->roles,
                'user_id' => $user->user_id
            ]);

            return redirect()->intended('home');
        } else {
            // Tambahkan pesan error
            return redirect()->back()->with('error', 'Email atau Password Salah');
        }
    }

    public function actionlogout()
    {
        session()->forget(['id', 'name', 'roles', 'user_id']);

        Auth::logout();
        return redirect('/');
    }
}
