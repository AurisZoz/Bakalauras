<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; 

class PictureController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                $publicId = basename($user->profile_photo, '.png'); 
                Cloudinary::destroy($publicId); 
            }

            $file = $request->file('profile_photo');
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'profile_photos',  
            ]);

            $user->profile_photo = $uploadedFile->getSecurePath();
            $user->save();

            return response()->json([
                'message' => 'Nuotrauka sėkmingai įkelta!',
                'profile_photo_url' => $user->profile_photo, 
            ]);
        }

        return response()->json(['message' => 'Klaida įkeliant nuotrauką.'], 400);
    }

    public function deletePhoto(Request $request)
    {
        try {
            $user = Auth::user(); 
            if ($user->profile_photo) {
                $publicId = basename($user->profile_photo, '.png');
                Cloudinary::destroy($publicId); 

                $user->profile_photo = null;  
                $user->save();
            }

            return response()->json([
                'message' => 'Nuotrauka sėkmingai ištrinta!',
                'profile_photo_url' => asset('img/profileuser.png')  
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Įvyko klaida ištrinant nuotrauką.'
            ], 500);
        }
    }
}
