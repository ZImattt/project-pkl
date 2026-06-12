<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Check if admin is logged in
     */
    private function checkAdminAuth()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.auth.login');
        }
        return null;
    }
    
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }
    
    /**
     * Handle login submission
     */
    public function login(Request $request)
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if ($request->username === 'admin' 
            && $request->password === 'global') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        }
        
        return back()->withErrors(['error' => 'Username atau password salah.']);
    }
    
    /**
     * Logout admin
     */
    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Logout berhasil!');
    }
}