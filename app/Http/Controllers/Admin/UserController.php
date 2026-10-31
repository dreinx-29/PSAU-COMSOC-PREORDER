<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin, 403);
    }

    public function index(): View
    {
        $this->authorizeAdmin();

        $admins = User::where('is_admin', true)
            ->orderBy('name')
            ->get();

        $users = User::where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('admins', 'users'));
    }

    public function promote(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($user->is_admin) {
            return back()->with('error', 'This account is already an admin.');
        }

        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'User successfully promoted to admin.');
    }

    public function demote(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        if (!$user->is_admin) {
            return back()->with('error', 'This account is not an admin.');
        }

        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'You cannot remove the last admin account.');
        }

        $user->is_admin = false;
        $user->save();

        return back()->with('success', 'Admin successfully demoted to regular user.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($user->is_admin) {
            return back()->with('error', 'Admin accounts cannot be deleted here.');
        }

        $user->delete();

        return back()->with('success', 'User account and associated pre-orders successfully deleted.');
    }
}
