<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManageUserController extends Controller
{
    /**
     * 1. INDEX - Menampilkan daftar user
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        // $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = $query->orderBy('created_at', 'desc')->get();

        return view('superadmin.index', compact('users'));
    }

    /**
     * 2. CREATE - Menampilkan form tambah user
     */
    public function create()
    {
        // Menggunakan path file langsung (sesuai request/trik Anda sebelumnya)
        // Alternatif standar Laravel: return view('superadmin.create.super');
        return view()->file(resource_path('views/superadmin/create.super.blade.php'));
    }

    /**
     * 3. STORE - Menyimpan data user baru ke database
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            // 'password' => 'required|min:6',
            'role'     => 'required', // Wajib dipilih dari dropdown
        ]);

        // LOGIKA ROLE:
        // Jika email @mhs.politala.ac.id -> Paksa jadi 'mahasiswa'
        // Jika bukan -> Gunakan role yang dipilih dari form
        if (str_ends_with($request->email, '@mhs.politala.ac.id')) {
            $role = 'mahasiswa';
        } else {
            $role = $request->role;
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            // 'password' => Hash::make($request->password),
            'role'     => $role, 
        ]);

        return redirect()->route('manage-users.index')
                         ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * 4. EDIT - Menampilkan form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Menggunakan path file langsung
        return view()->file(resource_path('views/superadmin/edit.super.blade.php'), ['user' => $user]);
    }

    /**
     * 5. UPDATE - Menyimpan perubahan data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi Input (Email boleh sama jika milik user itu sendiri)
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required',
            'password' => 'nullable|min:6', // Password boleh kosong
        ]);

        // LOGIKA ROLE (Sama seperti store)
        if (str_ends_with($request->email, '@mhs.politala.ac.id')) {
            $role = 'mahasiswa';
        } else {
            $role = $request->role;
        }

        // Siapkan data update
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $role,
        ];

        // Hanya update password jika input password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('manage-users.index')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * 6. DESTROY - Menghapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri saat sedang login.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}