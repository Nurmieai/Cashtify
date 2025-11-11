<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('livewire.user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = \App\Models\User::where('usr_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->usr_id},usr_id",
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'action' => 'nullable|string', // tambah ini untuk kontrol aksi foto
        ]);

        // === Aksi hapus foto ===
        if ($request->action === 'delete_photo') {
            if ($user->usr_card_url && file_exists(public_path($user->usr_card_url))) {
                unlink(public_path($user->usr_card_url));
            }

            $user->usr_card_url = null;
            $user->usr_img_public_id = null;
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'Foto profil dihapus.']);
        }

        // === Aksi update profil ===
        if ($request->hasFile('photo')) {
            // hapus foto lama
            if ($user->usr_card_url && file_exists(public_path($user->usr_card_url))) {
                unlink(public_path($user->usr_card_url));
            }

            // simpan foto baru
            $filename = 'user_' . $user->usr_id . '.' . $request->photo->extension();
            $path = $request->photo->storeAs('assets/images/profile', $filename, 'public');

            $user->usr_card_url = 'storage/' . $path;
        }



        // update data lain
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        Auth::setUser($user);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
