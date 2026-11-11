<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $data = User::select('id', 'name', 'username', 'role')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
            })
            ->paginate(10);
        
        return view('user.index', [
            'data' => $data,
        ]);
    }

    public function create()
    {
        return view('user.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users',
        'role' => 'required',
        'password' => 'required|min:3|confirmed', // 'confirmed' ini untuk mencocokkan password dengan konfirmasi password
    ]);

    User::create([
        'name' => $request->name,
        'username' => $request->username,
        'role' => $request->role,
        'password' => bcrypt($request->password), // atau Hash::make($request->password)
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}

    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|unique:users,username,' . $user->id,
            'role' => 'required',
            'password' => 'nullable|confirmed',
        ]);

        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'Data user berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Data user berhasil dihapus.');
    }
}