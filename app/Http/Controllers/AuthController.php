<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
	    	'username' => 'required',
	    	'password' => 'required',
	    ]);

	    if (Auth::attempt($validatedData)) {
	    	return redirect()->intended('/')->with('alert', ['success', 'Anda berhasil login']);
	    }
	    $dataSiswa = [
	    	'nis' => $validatedData['username'],
	    	'password' => $validatedData['password']
	    ];
		if (Auth::guard('siswa')->attempt($dataSiswa)) {
	    	return redirect()->intended('/')->with('alert', ['success', 'Anda berhasil login']);
		}

	    return back()->with('alert', ['error', 'Username atau password salah']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
 
	    $request->session()->invalidate();
	 
	    $request->session()->regenerateToken();
	 
	    return redirect('/login');
    }
}
