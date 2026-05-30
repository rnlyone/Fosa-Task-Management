<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => ['required', 'in:free,available,busy,very_busy,not_available,cant_be_bothered'],
        ]);

        auth()->user()->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }
}
