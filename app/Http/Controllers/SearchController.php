<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function index(Request $request) {
        $q     = $request->input('q', '');
        $users = collect();
        if (strlen($q) >= 1) {
            $users = User::where('login', 'like', "%{$q}%")
                         ->orWhere('name',  'like', "%{$q}%")
                         ->where('id', '!=', auth()->id())
                         ->limit(20)->get();
        }
        return view('search', compact('q', 'users'));
    }
}
