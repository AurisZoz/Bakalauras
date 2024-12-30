<?php

namespace App\Http\Controllers;

use App\Models\RehabilitationPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CreateController extends Controller
{
    public function create()
    {
        return view('plan.create-plan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'photos.*' => 'image', 
            'videos.*' => 'file|mimetypes:video/mp4,video/avi,video/mpeg',
            'media.*' => 'file', 
        ]);
    
        $plan = new RehabilitationPlan([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        $plan->save();
        try {
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $uploadedPhotoUrl = Cloudinary::upload($photo->getRealPath())->getSecurePath();
                    $plan->files()->create([
                        'file_path' => $uploadedPhotoUrl,
                        'original_file_name' => $photo->getClientOriginalName(),
                    ]);
                }}
            
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $uploadedVideoUrl = Cloudinary::uploadVideo($video->getRealPath(), [
                        'resource_type' => 'video'
                    ])->getSecurePath();
                    $plan->files()->create([
                        'file_path' => $uploadedVideoUrl,
                        'original_file_name' => $video->getClientOriginalName(),
                    ]);
                }}
            
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $filePath = $file->store('uploads', 'public');
                    $originalName = $file->getClientOriginalName();
                    $plan->files()->create([
                        'file_path' => $filePath,
                        'original_file_name' => $originalName,
                    ]);
                }}
            return redirect()->route('plan.index')->with('success', 'Jūsų sukurtas planas "' . $plan->title . '" sėkmingai įrašytas.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Nepavyko įkelti failų.'])->withInput();
        }
    }
    
}
