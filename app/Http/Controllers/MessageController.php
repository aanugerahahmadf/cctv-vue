<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            // Admin sees all messages
            $messages = Message::with(['sender', 'receiver'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function($message) {
                    return $message->sender_id < $message->receiver_id 
                        ? $message->sender_id . '-' . $message->receiver_id
                        : $message->receiver_id . '-' . $message->sender_id;
                });
        } else {
            // Regular users see only their messages
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function($message) use ($user) {
                    return $message->sender_id == $user->id 
                        ? $message->receiver_id 
                        : $message->sender_id;
                });
        }

        return Inertia::render('Messages/Index', [
            'messages' => $messages,
            'users' => User::where('id', '!=', $user->id)->get()
        ]);
    }

    public function show($userId): Response
    {
        $user = auth()->user();
        $otherUser = User::findOrFail($userId);
        
        $messages = Message::where(function($query) use ($user, $otherUser) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $otherUser->id);
        })->orWhere(function($query) use ($user, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('receiver_id', $user->id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $otherUser->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Inertia::render('Messages/Show', [
            'messages' => $messages,
            'otherUser' => $otherUser
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'message_type' => 'nullable|in:text,image,file',
        ]);

        $validated['sender_id'] = auth()->id();

        $message = Message::create($validated);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function markAsRead(Message $message): JsonResponse
    {
        if ($message->receiver_id === auth()->id()) {
            $message->update(['is_read' => true]);
        }

        return response()->json([
            'message' => 'Message marked as read'
        ]);
    }

    public function destroy(Message $message): JsonResponse
    {
        // Only sender can delete their own message
        if ($message->sender_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function getConversations(): JsonResponse
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            $conversations = Message::with(['sender', 'receiver'])
                ->select('sender_id', 'receiver_id')
                ->distinct()
                ->get()
                ->map(function($message) {
                    return [
                        'user_id' => $message->sender_id,
                        'user' => $message->sender,
                        'unread_count' => Message::where('sender_id', $message->sender_id)
                            ->where('receiver_id', auth()->id())
                            ->where('is_read', false)
                            ->count()
                    ];
                });
        } else {
            $conversations = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with(['sender', 'receiver'])
                ->get()
                ->groupBy(function($message) use ($user) {
                    return $message->sender_id == $user->id 
                        ? $message->receiver_id 
                        : $message->sender_id;
                })
                ->map(function($messages, $userId) use ($user) {
                    $otherUser = $messages->first()->sender_id == $user->id 
                        ? $messages->first()->receiver 
                        : $messages->first()->sender;
                    
                    return [
                        'user_id' => $userId,
                        'user' => $otherUser,
                        'unread_count' => $messages->where('receiver_id', $user->id)
                            ->where('is_read', false)
                            ->count()
                    ];
                });
        }

        return response()->json([
            'conversations' => $conversations->values()
        ]);
    }
}
