<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RehabilitationPlan;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function create()
    {
        $users = User::select('id', 'name', 'surname', 'phone')->get(); 
        $plans = RehabilitationPlan::select('id', 'title')->get(); 
    
        return view('plan.appointment', compact('users', 'plans'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:rehabilitation_plans,id',
            'start_date' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date',
            'comments' => 'nullable|string|max:1000',
        ]);

        Appointment::create([
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'comments' => $request->comments,
            'assigned_by' => Auth::id(), 
        ]);
    
        return redirect()->route('appointments.watch')->with('success', 'Paskyrimas sėkmingai sukurtas!');
    }

    public function watch(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'start_date'); 
        $order = $request->input('order', 'asc'); 
        
        $appointments = Appointment::with(['user', 'plan'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('surname', 'like', "%$search%");
                })->orWhereHas('plan', function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%");
                });
            })
            ->when($sortBy, function ($query) use ($sortBy, $order) {
                if ($sortBy == 'patient') {
                    $query->join('users', 'appointments.user_id', '=', 'users.id')
                          ->orderBy('users.name', $order); 
                } elseif ($sortBy == 'plan_title') {
                    $query->join('rehabilitation_plans', 'appointments.plan_id', '=', 'rehabilitation_plans.id')
                          ->orderBy('rehabilitation_plans.title', $order);
                } elseif ($sortBy == 'start_date') {
                    $query->orderBy('appointments.start_date', $order); 
                } elseif ($sortBy == 'end_date') {
                    $query->orderBy('appointments.end_date', $order); 
                }
            })
            ->paginate(8);
    
        return view('plan.appointment-watch', compact('appointments'));
    }
    
    

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        $users = User::select('id', 'name', 'surname', 'phone')->take(6)->get(); 
        $plans = RehabilitationPlan::select('id', 'title')->take(6)->get(); 

        return view('plan.appointment-edit', compact('appointment', 'users', 'plans'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:rehabilitation_plans,id',
        'start_date' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d'),
        'end_date' => 'required|date|after_or_equal:start_date',
        'comments' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
        'user_id' => $request->user_id,
        'plan_id' => $request->plan_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'comments' => $request->comments,
    ]);

    return redirect()->route('appointments.watch')->with('success', 'Paskyrimas sėkmingai atnaujintas!');
    }

    public function destroy($id)
    {
    $appointment = Appointment::findOrFail($id);

    $appointment->delete();

    return redirect()->route('appointments.watch')->with('success', 'Paskyrimas sėkmingai ištrintas!');
    }
}
