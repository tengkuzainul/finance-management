<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role/status
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($role === 'karyawan') {
                $query->where('is_admin', false);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(10)->withQueryString();

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }

        return view('pages.settings.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.settings.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama pengguna wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->has('is_admin');
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan!',
            'data' => $user,
            'redirect' => route('users.index')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View|JsonResponse
    {
        return view('pages.settings.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('pages.settings.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama pengguna wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin');
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui!',
            'data' => $user,
            'redirect' => route('users.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting the current logged-in user
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri!'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus!',
            'redirect' => route('users.index')
        ]);
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user): JsonResponse
    {
        // Prevent toggling current user's status
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah status akun Anda sendiri!'
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Pengguna berhasil {$status}!",
            'data' => $user
        ]);
    }
}
