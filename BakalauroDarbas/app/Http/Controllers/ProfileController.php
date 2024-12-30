<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('system.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ž\s]+$/',
            'surname' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ž\s]+$/',
            'phone' => 'required|numeric|unique:users',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Vardas yra privalomas',
            'name.regex' => 'Vardas turi būti sudarytas tik iš raidžių',
            'surname.required' => 'Pavardė yra privaloma',
            'surname.regex' => 'Pavardė turi būti sudaryta tik iš raidžių',
            'phone.required' => 'Telefono numeris yra privalomas',
            'phone.numeric' => 'Telefono numeris turi būti sudarytas iš skaičių',
            'email.required' => 'El. paštas yra privalomas',
            'email.email' => 'El. paštas turi būti tinkamo formato',
            'email.unique' => 'Šis el. paštas jau naudojamas',
        ]);
    
        $user->update($request->all());
    
        return redirect()->route('profile')->with('success', 'Profilio informacija sėkmingai atnaujinta.');
    }
    

    public function showChangePasswordForm()
    {
        return view('system.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);
    
        $user = Auth::user();
    
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => __('validation.custom.current_password.incorrect'),
            ]);
        }
    
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        return back()->with('success', 'Slaptažodis sėkmingai atnaujintas.');
    }
    

    public function showAccountManagement()
    {
        return view('/system/account-management');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Jūsų paskyra buvo sėkmingai ištrinta.');
    }

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
                'profile_photo_url' => asset('img/profileuser.png'),  
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Įvyko klaida ištrinant nuotrauką.'
            ], 500);
        }
    }
}
