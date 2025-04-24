<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $breadcrumb = (object)[
            'title' => 'Profil Pengguna',
            'list' => ['Home', 'Profil']
        ];

        return view('profil.index', [
            'user' => $user,
            'activeMenu' => 'profil',
            'breadcrumb' => $breadcrumb
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/foto_profil/' . $user->foto);
            }

            $filename = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('public/foto_profil', $filename);
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('profil.index')->with('success', 'Foto profil berhasil diperbarui.');
    }
}