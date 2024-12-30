<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RehabilitationPlan;
use App\Models\PlanFile; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'media.*' => 'nullable|file|max:51200', 
        ]);

        $plan = RehabilitationPlan::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $filePath = $file->store('uploads', 'public');
                $originalName = $file->getClientOriginalName();

                $plan->files()->create([
                    'file_path' => $filePath,
                    'original_file_name' => $originalName,
                ]);
            }
        }

        return response()->json(['message' => 'Įrašas sėkmingai sukurtas.'], 200);
    }

    public function destroy($id)
    {
        $file = PlanFile::findOrFail($id); 
        Storage::disk('public')->delete($file->file_path); 
        $file->delete(); 
    
        return response()->json(['message' => 'Failas sėkmingai ištrintas.'], 200);
    }
    
    public function download($id)
    {
        $file = PlanFile::findOrFail($id); 
    
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $file->file_path));
    }
    
}
