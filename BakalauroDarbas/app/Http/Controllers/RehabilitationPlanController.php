<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RehabilitationPlan;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanFile;
use Cloudinary\Cloudinary;
use Cloudinary\Uploader;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Support\Str;

class RehabilitationPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'title'); 
        $order = $request->input('order', 'asc');      

        $plans = RehabilitationPlan::where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy($sortBy, $order)
            ->paginate(8); 

        return view('plan.plan-control', compact('plans'));
    }

    public function show($id)
    {
        $plan = RehabilitationPlan::with('files', 'user')->findOrFail($id);
        return view('plan.show', compact('plan'));
    }
    
    public function showApi($id)
    {
        $plan = RehabilitationPlan::with('user', 'files')->findOrFail($id);
        return response()->json($plan); 
    }

    public function edit($id)
    {
        $plan = RehabilitationPlan::findOrFail($id);
        return view('plan.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = RehabilitationPlan::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'media.*' => 'nullable|file|max:51200', 
        ]);

        $plan->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
 
        if ($request->filled('deleted_files')) {
            $deletedFiles = explode(',', $request->deleted_files);
            foreach ($deletedFiles as $fileId) {
                $file = PlanFile::find($fileId);
                if ($file) {
                    try {
                        if (filter_var($file->file_path, FILTER_VALIDATE_URL)) {
                            $cloudinary = new \Cloudinary\Cloudinary([
                                'cloud' => [
                                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                                    'api_key'    => env('CLOUDINARY_API_KEY'),
                                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                                ],
                            ]);
                            $cloudinary->uploadApi()->destroy($file->file_path);
                        } else {
                            \Storage::disk('public')->delete($file->file_path);
                        }
                        $file->delete();
                    } catch (\Exception $e) {
                        \Log::error("Nepavyko ištrinti failo: " . $e->getMessage());
                    }
                }
            }
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                try {
                    $filePath = '';

                    if (Str::contains($file->getMimeType(), 'video')) {
                        $cloudinary = new \Cloudinary\Cloudinary([
                            'cloud' => [
                                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                                'api_key'    => env('CLOUDINARY_API_KEY'),
                                'api_secret' => env('CLOUDINARY_API_SECRET'),
                            ],
                        ]);
                        $upload = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                            'folder' => 'rehabilitation_plans/videos',
                            'resource_type' => 'video',
                        ]);
                        $filePath = $upload['secure_url'];
                    }
                    elseif (Str::contains($file->getMimeType(), 'image')) {
                        $cloudinary = new \Cloudinary\Cloudinary([
                            'cloud' => [
                                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                                'api_key'    => env('CLOUDINARY_API_KEY'),
                                'api_secret' => env('CLOUDINARY_API_SECRET'),
                            ],
                        ]);
                        $upload = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                            'folder' => 'rehabilitation_plans/images',
                        ]);
                        $filePath = $upload['secure_url'];
                    } else {
                        $filePath = $file->store('uploads', 'public');
                    }
                    $plan->files()->create([
                        'file_path' => $filePath,
                        'original_file_name' => $file->getClientOriginalName(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Nepavyko įkelti failo: " . $e->getMessage());
                }
            }
        }
    
        return redirect()->route('plan.index')->with('success', 'Planas "' . $plan->title . '" sėkmingai atnaujintas.');
    }
    
    public function destroy($id)
    {
        $plan = RehabilitationPlan::findOrFail($id);
        $plan->delete();
        
        if ($plan->user_id !== Auth::id()) {
            return redirect()->route('plan.index');
        }

        return redirect()->route('plan.index')->with('success', 'Planas buvo sėkmingai ištrintas.');
    }

    public function allPlans(Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at'); 
        $order = $request->get('order', 'asc'); 
        $search = $request->get('search'); 
    
        $plans = RehabilitationPlan::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($query) use ($search) {
                                 $query->where('name', 'like', "%{$search}%")
                                       ->orWhere('surname', 'like', "%{$search}%");
                             });
            })
            ->when(in_array($sortBy, ['name', 'surname']), function ($query) use ($sortBy, $order) {
                return $query->join('users', 'rehabilitation_plans.user_id', '=', 'users.id')
                             ->orderBy("users.$sortBy", $order);
            }, function ($query) use ($sortBy, $order) {
                return $query->orderBy($sortBy, $order);
            })
            ->paginate(8);
    
        return view('plan.all-plans', compact('plans'));
    }
    
    public function savedPlans(Request $request)
    {
        $sortBy = $request->input('sort_by', 'title'); 
        $order = $request->input('order', 'asc'); 
        $search = $request->input('search'); 
    
        $savedPlans = auth()->user()->savedPlans()
            ->with('user')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                             ->orWhereHas('user', function ($query) use ($search) {
                                 $query->where('name', 'like', '%' . $search . '%')
                                       ->orWhere('surname', 'like', '%' . $search . '%');
                             });
            })
            ->when(in_array($sortBy, ['name', 'surname']), function ($query) use ($sortBy, $order) {
                return $query->join('users', 'rehabilitation_plans.user_id', '=', 'users.id')
                             ->orderBy("users.$sortBy", $order);
            }, function ($query) use ($sortBy, $order) {
                return $query->orderBy($sortBy, $order);
            })
            ->paginate(8);
    
        return view('plan.saved-plans', compact('savedPlans'));
    }
    
    public function save($id)
    {
        $user = auth()->user();
        $user->savedPlans()->attach($id);

        return redirect()->route('plan.all-plans')->with('success', 'Planas sėkmingai išsaugotas.');
    }

    public function unsave($id)
    {
        $user = auth()->user();
        $plan = $user->savedPlans()->findOrFail($id);
        $user->savedPlans()->detach($id);

        return redirect()->route('plan.saved-plans')->with('success', 'Planas "' . $plan->title . '" sėkmingai pašalintas iš išsaugotų planų sąrašo.');
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_plans', 'plan_id', 'user_id');
    }

    public function getUserPlans($userId)
    {
        $plans = RehabilitationPlan::where('user_id', $userId)
            ->select('id', 'title', 'created_at', 'updated_at')
            ->get()
            ->map(function ($plan) {
                $plan->created_at = $plan->created_at->format('Y-m-d');
                return $plan;
            });
    
        return response()->json($plans);
    }
}
