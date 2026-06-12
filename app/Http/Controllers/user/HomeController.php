<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function pilihTipe()
    {
        return view('user.pilih-tipe');
    }

    public function prosesPilihTipe(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:individu,kelompok'
        ]);

        if ($request->tipe == 'individu') {
            return redirect()->route('user.register.individu');
        } else {
            return redirect()->route('user.kelompok.create');
        }
    }
}