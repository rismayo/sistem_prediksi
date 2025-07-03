<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('datauser', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:tbl_user,username',
            'email' => 'required|email|unique:tbl_user,email',
            'level' => 'required|in:superadmin,admin',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:tbl_user,username,' . $id_user . ',id_user',
            'email' => 'required|email|unique:tbl_user,email,' . $id_user . ',id_user',
            'level' => 'required|in:superadmin,admin',
            'password' => 'nullable|string|min:6',
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->level = $request->level;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function updatePassword(Request $request, $id_user)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::findOrFail($id_user);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('datauser')->with('success', 'Password berhasil diperbarui.');
    }
}
