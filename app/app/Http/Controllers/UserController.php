<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache; // pakai cache untuk simpan kode sementara
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    // Register user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|string|min:6|max:100',
            'role' => 'required|in:superadmin,owner,karyawan',
            'status' => 'sometimes|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status ?? 'aktif',
        ]);

        return response()->json([
            'message' => 'User berhasil dibuat',
            'user' => $user
        ], 201);
    }

    // Login user & buat token
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email|max:50',
        'password' => 'required|string|max:100',
    ]);

    $user = User::where('email', $request->email)->first();

    // Cek user ada dan password sesuai
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // Cek status aktif
    if ($user->status !== 'aktif') {
        return response()->json(['message' => 'Akun Anda nonaktif, tidak bisa login'], 403);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'user' => $user,
        'token' => $token
    ]);
}

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    // List semua user
    // List semua user dengan pagination 5 per halaman
    public function index(Request $request)
{
    if ($request->boolean('all')) {
        $users = User::orderBy('id', 'desc')->get();
    } else {
        $users = User::orderBy('id', 'desc')->paginate(10);
    }

    return response()->json($users);
}


    

    // Lihat detail user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update user
    // Update user
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'sometimes|string|max:40',
        'email' => 'sometimes|email|unique:users,email,' . $id . '|max:50',
        'password' => 'sometimes|string|min:6|max:100',
        'role' => 'sometimes|in:superadmin,owner,karyawan',
        'status' => 'sometimes|in:aktif,nonaktif',
    ]);

    // Cek jika sedang login, jangan boleh ubah status sendiri
    if ($id == auth()->id() && $request->has('status')) {
        return response()->json([
            'message' => 'Tidak bisa mengubah status akun yang sedang login'
        ], 403);
    }

    if ($request->has('password')) {
        $request->merge(['password' => bcrypt($request->password)]);
    }

    $user->update($request->only(['name', 'email', 'password', 'role', 'status']));

    return response()->json([
        'message' => 'User berhasil diupdate',
        'user' => $user
    ]);
}


    // Hapus user
    public function destroy($id)
    {
        // Cek apakah user yang akan dihapus adalah user login saat ini
        if ($id == auth()->id()) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun yang sedang login'
            ], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus']);
    }
    public function requestResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $code = rand(100000, 999999); // kode 6 digit

        // Simpan kode di cache selama 10 menit
        Cache::put('reset_password_'.$request->email, $code, now()->addMinutes(10));

        // Kirim email
        Mail::raw("Kode reset password Anda: $code (berlaku 10 menit)", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password');
        });

        return response()->json([
            'message' => 'Kode reset password telah dikirim ke email Anda'
        ]);
    }

    // Reset password dengan kode
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6',
            'password' => 'required|string|min:6|confirmed', // field password_confirmation harus dikirim juga
        ]);

        $cachedCode = Cache::get('reset_password_'.$request->email);

        if (!$cachedCode || $cachedCode != $request->code) {
            return response()->json(['message' => 'Kode tidak valid atau sudah kadaluarsa'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus kode dari cache
        Cache::forget('reset_password_'.$request->email);

        return response()->json(['message' => 'Password berhasil direset']);
    }
}
