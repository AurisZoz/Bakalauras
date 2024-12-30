<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showUserProfiles(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
    
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('id', '=', $search)
                             ->orWhere('name', 'LIKE', "%{$search}%")
                             ->orWhere('surname', 'LIKE', "%{$search}%");
            })
            ->orderBy($sort, $order)
            ->paginate(8);
    
        return view('admin.userprofile', compact('users'));
    }
    

    public function showUserControl()
    {
        $users = User::all(); 
        return view('admin.userprofile', compact('users'));
    }

    public function showUser($id, Request $request)
    {
        $user = User::findOrFail($id);
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'asc');
    
        $plans = $user->rehabilitationPlans()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy($sort, $order)
            ->paginate(8);
    
        return view('admin.usercontrol-show', compact('user', 'plans'));
    }
    
}
