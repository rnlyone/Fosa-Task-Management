<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('departments')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($b) use ($q) {
                $b->where('name', 'like', "%$q%")
                  ->orWhere('username', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members     = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('members.index', compact('members', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('members.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'username'    => ['required', 'string', 'max:50', 'unique:users'],
            'email'       => ['required', 'email', 'unique:users'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', 'in:president,vice_president,member'],
            'status'      => ['required', 'in:free,available,busy,very_busy,not_available,cant_be_bothered'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'status'   => $data['status'],
        ]);

        if (!empty($data['departments'])) {
            $user->departments()->sync($data['departments']);
        }

        return redirect()->route('members.index')
            ->with('success', 'Member added successfully.');
    }

    public function edit(User $member)
    {
        $member->load('departments');
        $departments = Department::orderBy('name')->get();
        return view('members.edit', compact('member', 'departments'));
    }

    public function update(Request $request, User $member)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'username'    => ['required', 'string', 'max:50', Rule::unique('users')->ignore($member)],
            'email'       => ['required', 'email', Rule::unique('users')->ignore($member)],
            'role'        => ['required', 'in:president,vice_president,member'],
            'status'      => ['required', 'in:free,available,busy,very_busy,not_available,cant_be_bothered'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['exists:departments,id'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $update = [
            'name'     => $data['name'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'status'   => $data['status'],
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $member->update($update);
        $member->departments()->sync($data['departments'] ?? []);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->route('members.index')
            ->with('success', 'Member removed.');
    }
}
