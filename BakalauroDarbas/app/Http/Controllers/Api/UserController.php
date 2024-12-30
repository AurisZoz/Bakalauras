<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; 
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Contact;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('query');

        $users = User::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('surname', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($users);
    }

    public function index()
    {
        return view('admin.usercontrol');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|regex:/^\+?[0-9]{1,15}$/',
            'role' => 'required|in:user,doctor,admin',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $user = User::findOrFail($id);
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
        }

        $user->update($request->all());

        return redirect()->route('usercontrol.show', $id)->with('success', 'Naudotojo informacija sėkmingai atnaujinta.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile_photo) {
            $publicId = basename($user->profile_photo, '.png'); 
            Cloudinary::destroy($publicId);
        }

        $user->delete();

        return redirect()->route('usercontrol.index')->with('success', 'Naudotojas sėkmingai ištrintas.');
    }

    public function userView()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)
            ->with(['plan', 'assignedBy']) 
            ->get(); 
        return view('plan.userview', compact('appointments'));
    }

    public function getContacts()
    {
        $contacts = Contact::with('contact') 
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($contact) {
                return $contact->contact; 
            });

        return response()->json($contacts, 200);
    }

    public function addContact(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id',
        ]);

        $existingContact = Contact::where('user_id', Auth::id())
            ->where('contact_id', $request->contact_id)
            ->first();

        if (!$existingContact) {
            Contact::create([
                'user_id' => Auth::id(),
                'contact_id' => $request->contact_id,
            ]);
        }

        return response()->json(['message' => 'Kontaktas pridėtas.'], 201);
    }

    public function saveContacts(Request $request)
    {
        $request->validate(['contacts' => 'required|array']);
        $user = Auth::user();

        $contacts = collect($request->contacts)->map(function ($contactId) {
            return ['contact_id' => $contactId];
        });

        $user->contacts()->sync($contacts);

        return response()->json(['message' => 'Kontaktai išsaugoti.']);
    }

    public function removeContact(Request $request)
    {
        $request->validate([
        'contact_id' => 'required|exists:users,id',
        ]);

        $contact = Auth::user()->contacts()->where('contact_id', $request->contact_id)->first();
        if ($contact) {
        $contact->delete();
        return response()->json(['message' => 'Kontaktas sėkmingai pašalintas.']);
        }

        return response()->json(['message' => 'Kontaktas nerastas.'], 404);
    }

    public function userContacts(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');

        $contacts = User::whereIn('role', ['doctor', 'admin'])
        ->when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%")
        ->orWhere('surname', 'like', "%{$search}%")
        ->orWhere('phone', 'like', "%{$search}%");
        })
        ->orderBy($sort, $order)
        ->paginate(8);

        return view('plan.usercontact', compact('contacts'));
    }
}
