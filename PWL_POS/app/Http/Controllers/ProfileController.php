<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Data Profile'
        ];

        $activeMenu = 'Profile';
      
        return view('profile.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = auth()->user();

            if ($user->foto && Storage::disk('public')->exists('profile/' . $user->foto)) {
                Storage::disk('public')->delete('profile/' . $user->foto);
            }
         
            $fileName = time() . '.' . $request->foto->extension();
            $request->foto->storeAs('profile', $fileName, 'public');

            $user->foto = $fileName;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diunggah!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload Gagal!'
            ], 500);
        }
    }
}
