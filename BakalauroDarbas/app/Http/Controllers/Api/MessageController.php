<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($userId)
    {
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('from_user_id', Auth::id())
                  ->where('to_user_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('from_user_id', $userId)
                  ->where('to_user_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
    
        return response()->json($messages);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
        ]);
    
        try {
            $message = Message::create([
                'from_user_id' => Auth::id(),
                'to_user_id' => $validated['to_user_id'],
                'content' => $validated['content'],
                'is_read' => false, 
            ]);

            $this->addContact(Auth::id(), $validated['to_user_id']);
            $this->addContact($validated['to_user_id'], Auth::id());
    
            return response()->json($message, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    private function addContact($userId, $contactId)
    {
        \App\Models\Contact::firstOrCreate([
            'user_id' => $userId,
            'contact_id' => $contactId,
        ]);
    }
    

    public function markMessagesAsRead($userId)
    {
        $user = Auth::id();
        Message::where('from_user_id', $userId)
        ->where('to_user_id', $user)
        ->where('is_read', false)
        ->update(['is_read' => true]);
        return response()->json(['message' => 'Žinutės pažymėtos kaip skaitytos.'], 200);
    }

}
