<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    public function index()
    {
        $waitlists = Waitlist::with('product')->latest()->paginate(15);
        return view('admin.waitlists.index', compact('waitlists'));
    }
}
