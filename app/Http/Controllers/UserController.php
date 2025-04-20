<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   // Menampilkan daftar pengguna
   public function index()
   {
    $users = User::paginate(30); // Ambil semua pengguna
       return view('users.index', compact('users'));
   }

   // Menampilkan form untuk menambahkan pengguna
   public function create()
   {
       return view('users.create');
   }

   // Menyimpan pengguna baru
   public function store(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:8|confirmed',
           'role' => 'required|in:admin,reviewer', // Pastikan role valid
       ]);

       User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password),
           'role' => $request->role,
       ]);

       return redirect()->route('users.index')->with('success', 'User  berhasil ditambahkan.');
   }

   // Menampilkan form untuk mengedit pengguna
   public function edit(User $user)
   {
       return view('users.edit', compact('user'));
   }

   // Memperbarui pengguna
   public function update(Request $request, User $user)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
           'password' => 'nullable|string|min:8|confirmed',
           'role' => 'required|in:admin,reviewer',
       ]);

       $user->name = $request->name;
       $user->email = $request->email;
       if ($request->password) {
           $user->password = Hash::make($request->password);
       }
       $user->role = $request->role;
       $user->save();

       return redirect()->route('users.index')->with('success', 'User  berhasil diperbarui.');
   }

   // Menghapus pengguna
   public function destroy(User $user)
   {
       $user->delete();
       return redirect()->route('users.index')->with('success', 'User  berhasil dihapus.');
   }
}
